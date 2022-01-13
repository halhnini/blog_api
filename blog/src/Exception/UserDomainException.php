<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Exception;

/**
 * Class UserDomainException
 */
class UserDomainException extends BlogDomainException
{
    const DEFAULT_TRACE_CODE = 'USER_DOMAIN_ERROR';
    const FORM_VALIDATION_TRACE_CODE = 'FORM_VALIDATION_ERROR';
    const FORM_VALIDATION_MESSAGE = 'error.user.form_validation';
    const ACTIVATION_USER_MESSAGE = 'invalid.user.token';
    const FORM_USER_PASSWORD = 'invalid.user.password';
}
