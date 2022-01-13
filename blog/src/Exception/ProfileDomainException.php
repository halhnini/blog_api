<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Exception;

/**
 * Class ProfileDomainException
 */
class ProfileDomainException extends BlogDomainException
{
    const DEFAULT_TRACE_CODE = 'PROFILE_DOMAIN_ERROR';
    const FORM_VALIDATION_TRACE_CODE = 'FORM_VALIDATION_ERROR';
    const FORM_VALIDATION_MESSAGE = 'error.profile.form_validation';
}
