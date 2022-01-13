<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Normalizer;

use App\Model\Error;

/**
 * Interface ExceptionNormalizerInterface
 */
interface ExceptionNormalizerInterface
{
    /**
     * Normalizes an exception into an Error object.
     *
     * @param mixed $exception
     *
     * @return Error
     */
    public function normalize($exception): Error;

    /**
     * Checks whether the given exception is supported for normalization by this normalizer.
     *
     * @param mixed $exception
     *
     * @return bool
     */
    public function support($exception): bool;

    /**
     * Gets normalizer priority.
     *
     * @return int
     */
    public function getPriority(): int;
}
