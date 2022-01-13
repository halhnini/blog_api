<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Form\Transformer;

use App\Utils\GenderHelper;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class GenderDataTransformer
 */
class GenderDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($value)
    {
        if (!is_int($value)) {
            return null;
        }

        try {
            return GenderHelper::getGenderLabel($value);
        } catch (\InvalidArgumentException $exception) {
            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($value)
    {
        if (!is_string($value)) {
            return null;
        }

        try {
            return GenderHelper::getGenderCode($value);
        } catch (\InvalidArgumentException $exception) {
            return null;
        }
    }
}
