<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Form\Transformer;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class EntitiesDataTransformer
 */
class EntitiesDataTransformer extends EntityDataTransformer
{
    /**
     * {@inheritDoc}
     */
    public function reverseTransform($value)
    {
        if (empty($value)) {
            return [];
        }

        if (!is_array($value)) {
            throw new \InvalidArgumentException('The given value must be an array !');
        }

        $identifiers = [];
        foreach ($value as $item) {
            if (empty($item)) {
                continue;
            }

            $identifiers[] = $this->getIdentifier($item);
        }

        $entities = $this->entityManager->getRepository($this->entityClass)->findBy([
            $this->getIdentifierName() => $identifiers,
        ]);

        return new ArrayCollection($entities);
    }
}
