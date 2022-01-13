<?php
/*
 * This file is part of a Geniuses project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Exception;

/**
 * Class CompanyIdentityException
 */
class PostException extends BlogDomainException
{
    const DEFAULT_TRACE_CODE = 'POST_DOMAIN_ERROR';
    const FORM_VALIDATION_TRACE_CODE = 'FORM_VALIDATION_ERROR';
    const FORM_VALIDATION_MESSAGE = 'error.post.form_validation';
    const COMPANY_IDENTITY_ALREADY_EXISTS = 'error.post.already_exists';
}
