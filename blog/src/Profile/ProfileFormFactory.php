<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Profile;

use Symfony\Component\Form\{
    FormFactoryInterface,
    FormInterface
};
use App\Utils\ProfileHelper;
use Geniuses\Union\Api\Profile\AdministratorType;

/**
 * Class ProfileFormFactory
 */
class ProfileFormFactory implements ProfileFormFactoryInterface
{
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * ProfileFormFactory constructor.
     *
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function create(string $profileType, $data = null, array $options = []): FormInterface
    {
        switch ($profileType) {
            case ProfileHelper::AUTHOR_PROFILE:
                $type = AuthorType::class;
                break;
            case ProfileHelper::EDITOR_PROFILE:
                $type = EditorType::class;
                break;
            case ProfileHelper::CONTRIBUTOR_PROFILE:
                $type = ContributorType::class;
                break;
            case ProfileHelper::SUBSCRIBER_PROFILE:
                $type = SubscriberType::class;
                break;
            case ProfileHelper::ADMINISTRATOR_PROFILE:
                $type = AdministratorType::class;
                break;
            default:
                throw new \InvalidArgumentException(
                    sprintf('Invalid profile type [%s] given !', $profileType)
                );
        }

        return $this->formFactory->create($type, $data, $options);
    }
}
