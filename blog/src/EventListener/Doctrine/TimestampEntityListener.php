<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\EventListener\Doctrine;

/**
 * Class TimestampEntityListener
 */
class TimestampEntityListener
{
    /**
     * @param mixed $entity
     *
     * @throws \Exception
     */
    public function prePersist($entity): void
    {
        $dateTime = new \DateTime();
        $this->setTimeStamp('setCreatedAt', $entity, $dateTime);
        $this->setTimeStamp('setUpdatedAt', $entity, $dateTime);
    }

    /**
     * @param mixed $entity
     *
     * @throws \Exception
     */
    public function preUpdate($entity): void
    {
        $this->setTimeStamp('setUpdatedAt', $entity, new \DateTime());
    }

    /**
     * @param string    $setter
     * @param mixed     $entity
     * @param \DateTime $dateTime
     *
     * @throws \Exception
     */
    private function setTimeStamp(string $setter, $entity, \DateTime $dateTime): void
    {
        if (!is_callable([$entity, $setter])) {
            return;
        }

        $entity->{$setter}($dateTime);
    }
}
