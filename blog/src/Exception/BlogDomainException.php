<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Exception;

/**
 * Class BlogDomainException
 */
class BlogDomainException extends BlogException
{
    /**
     * define if the exception type message must be rendered or not.
     *
     * @var bool
     */
    protected static $rendered = true;
}
