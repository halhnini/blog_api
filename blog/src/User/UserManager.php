<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\User;

use Doctrine\ORM\EntityManagerInterface;
use App\{
    Entity\User,
    Exception\UserDomainException,
    Repository\UserRepositoryInterface,
    Utils\ProfileHelper
};
use Symfony\Component\{
    Form\FormFactoryInterface,
    HttpFoundation\Response,
    Security\Core\Encoder\UserPasswordEncoderInterface,
    Validator\Validator\ValidatorInterface
};
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class UserManager
 */
class UserManager implements UserManagerInterface
{
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * UserManager constructor.
     *
     * @param FormFactoryInterface         $formFactory
     * @param ValidatorInterface           $validator
     * @param UserRepositoryInterface      $userRepository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param EntityManagerInterface       $entityManager
     */
    public function __construct(FormFactoryInterface $formFactory, ValidatorInterface $validator, UserRepositoryInterface $userRepository, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager)
    {
        $this->formFactory = $formFactory;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function createUser(array $userData, string $profile): User
    {
        $user = UserFactory::create();
        $form = $this->formFactory->create(UserType::class, $user);
        $form->submit($userData);
        $this->validateUser($user, ['create']);
        $user->addRole(ProfileHelper::getProfileRole($profile));
        $user->setToken(uuid_create(UUID_TYPE_RANDOM));

        return $this->saveUser($user);
    }

    /**
     * {@inheritDoc}
     */
    public function saveUser(User $user): User
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function activateUser(User $user, string $token): void
    {
        if ($user->getToken() !== $token) {
            throw new UserDomainException(UserDomainException::ACTIVATION_USER_MESSAGE);
        }
        $user->setActive(true);
    }

    /**
     * {@inheritDoc}
     */
    public function resetUserPassword(User $user, string $oldPassword, string $newPassword): void
    {
        $password = new ChangePasswordData($oldPassword, $newPassword);
        $this->validatePassword($password, ['reset_password']);
        $user->setPlainPassword($password->getNewPassword());
        $this->encodeUserPassword($user);
        $user->eraseCredentials();
    }

    /**
     * {@inheritDoc}
     */
    public function encodeUserPassword(User $user): void
    {
        if (empty($user->getPlainPassword())) {
            return;
        }
        $user->setPassword(
            $this->userPasswordEncoder->encodePassword($user, $user->getPlainPassword())
        );
    }

    /**
     * {@inheritDoc}
     */
    public function fetchUser(int $userId): User
    {
        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw new EntityNotFoundException(
                sprintf('User [%d] not found !', $userId),
                Response::HTTP_NOT_FOUND
            );
        }

        return $user;
    }

    /**
     * @param ChangePasswordData $password
     * @param array              $validationGroups
     *
     * @throws UserDomainException
     */
    private function validatePassword(ChangePasswordData $password, array $validationGroups): void
    {
        $errors = $this->validator->validate($password, null, $validationGroups);
        if (0 === $errors->count()) {
            return;
        }

        throw new UserDomainException(
            UserDomainException::FORM_USER_PASSWORD,
            UserDomainException::FORM_VALIDATION_TRACE_CODE,
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @param User  $user
     * @param array $validationGroups
     *
     * @throws UserDomainException
     */
    private function validateUser(User $user, array $validationGroups): void
    {
        $errors = $this->validator->validate($user, null, $validationGroups);
        //var_dump($errors);die;
        if (0 === $errors->count()) {
            return;
        }

        throw new UserDomainException(
            UserDomainException::FORM_VALIDATION_MESSAGE,
            UserDomainException::FORM_VALIDATION_TRACE_CODE,
            Response::HTTP_BAD_REQUEST
        );
    }
}
