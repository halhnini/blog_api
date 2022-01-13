<?php
/*
 * This file is part of a Geniuses project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Exception;

/**
 * Class CommentException
 */
class CommentException extends BlogDomainException
{
    const DEFAULT_TRACE_CODE = 'COMMENT_DOMAIN_ERROR';
    const FORM_VALIDATION_TRACE_CODE = 'FORM_VALIDATION_ERROR';
    const FORM_VALIDATION_MESSAGE = 'error.comment.form_validation';
    const COMPANY_IDENTITY_ALREADY_EXISTS = 'error.comment.already_exists';
}
