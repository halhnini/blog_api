<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Profile;

use Doctrine\ORM\EntityManagerInterface;
use App\{
    Entity\AbstractProfile,
    Exception\ProfileDomainException,
    Repository\ProfileRepositoryInterface
};
use Symfony\Component\{
    HttpFoundation\Request,
    HttpFoundation\Response,
    Validator\Validator\ValidatorInterface
};

use Doctrine\ORM\EntityNotFoundException;

/**
 * Class ProfileManager
 */
class ProfileManager implements ProfileManagerInterface
{
    /**
     * @var ProfileRepositoryInterface
     */
    private ProfileRepositoryInterface $profileRepository;

    /**
     * @var ProfileFormFactoryInterface
     */
    private ProfileFormFactoryInterface $formFactory;

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * ProfileManager constructor.
     *
     * @param ProfileRepositoryInterface  $profileRepository
     * @param ProfileFormFactoryInterface $formFactory
     * @param ValidatorInterface          $validator
     * @param EntityManagerInterface      $entityManager
     */
    public function __construct(ProfileRepositoryInterface $profileRepository, ProfileFormFactoryInterface $formFactory, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->profileRepository = $profileRepository;
        $this->formFactory = $formFactory;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function addProfile(ProfileData $profileData): AbstractProfile
    {
        $profile = $this->createProfile($profileData);
        $this->saveProfile($profile, true);

        return $profile;
    }

    /**
     * {@inheritDoc}
     */
    public function updateProfile(ProfileData $profileData): AbstractProfile
    {
        if (null === $profile = $profileData->profile) {
            throw new \InvalidArgumentException('Invalid profile data given, profile is required !');
        }

        $form = $this->formFactory->create($profileData->type, $profile, [
            'method' => Request::METHOD_PATCH,
        ]);
        $form->submit($profileData->formData, false);
        $this->validateProfile($profile, ['update']);
        $this->saveProfile($profile, true);

        return $profile;
    }

    /**
     * {@inheritDoc}
     */
    public function createProfile(ProfileData $profileData): AbstractProfile
    {
        $profile = ProfileFactory::create($profileData->type);
        $form = $this->formFactory->create($profileData->type, $profile);
        $form->submit($profileData->formData);
        $profile->setUser($profileData->user);
        $this->validateProfile($profile, ['create']);

        return $profile;
    }

    /**
     * {@inheritDoc}
     */
    public function saveProfile(AbstractProfile $profile, bool $flush = false): void
    {
        $this->entityManager->persist($profile);
        if (true === $flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function fetchUserProfile(int $userId): AbstractProfile
    {
        $profile = $this->profileRepository->findOneBy([
            'user' => $userId,
        ]);
        if (!$profile) {
            throw new EntityNotFoundException(
                sprintf('Profile not found for the given user [%d] !', $userId),
                Response::HTTP_NOT_FOUND
            );
        }

        return $profile;
    }

    /**
     * @param int $profileId
     *
     * @return AbstractProfile
     *
     * @throws EntityNotFoundException
     */
    public function fetchProfile(int $profileId): AbstractProfile
    {
        $profile = $this->profileRepository->findOneBy([
            'id' => $profileId,
        ]);
        if (!$profile) {
            throw new EntityNotFoundException(
                sprintf('Profile not found for the given id [%d] !', $profileId),
                Response::HTTP_NOT_FOUND
            );
        }

        return $profile;
    }

    /**
     * @param AbstractProfile $profile
     * @param array           $validationGroups
     *
     * @throws ProfileDomainException
     */
    private function validateProfile(AbstractProfile $profile, array $validationGroups): void
    {
        $errors = $this->validator->validate($profile, null, $validationGroups);
        if (0 === $errors->count()) {
            return;
        }

        throw new ProfileDomainException(
            ProfileDomainException::FORM_VALIDATION_MESSAGE,
            ProfileDomainException::FORM_VALIDATION_TRACE_CODE,
            Response::HTTP_BAD_REQUEST
        );
    }
}
