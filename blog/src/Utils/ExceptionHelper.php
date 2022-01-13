<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Utils;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ExceptionHelper
 */
class ExceptionHelper
{
    const ACCESS_DENIED_TRACE_CODE = 'ACCESS_DENIED_ERROR';
    const BAD_REQUEST_TRACE_CODE = 'BAD_REQUEST_ERROR';
    const UNAUTHORIZED_TRACE_CODE = 'UNAUTHORIZED_ERROR';
    const NOT_FOUND_TRACE_CODE = 'NOT_FOUND_ERROR';
    const METHOD_NOT_ALLOWED_TRACE_CODE = 'METHOD_NOT_ALLOWED_ERROR';
    const ACCESS_DENIED_MESSAGE = 'ACCESS_DENIED_MESSAGE';
    const BAD_REQUEST_MESSAGE = 'BAD_REQUEST_MESSAGE';
    const UNAUTHORIZED_MESSAGE = 'UNAUTHORIZED_MESSAGE';
    const NOT_FOUND_MESSAGE = 'NOT_FOUND_MESSAGE';
    const METHOD_NOT_ALLOWED_MESSAGE = 'METHOD_NOT_ALLOWED_MESSAGE';
    const GENERIC_MESSAGE = 'METHOD_NOT_ALLOWED_MESSAGE';
    const DEFAULT_TRACE_CODE = 'GENARAL_ERROR';

    const HTTP_TRACE_CODES = [
        Response::HTTP_BAD_REQUEST => self::BAD_REQUEST_TRACE_CODE,
        Response::HTTP_FORBIDDEN => self::ACCESS_DENIED_TRACE_CODE,
        Response::HTTP_UNAUTHORIZED => self::UNAUTHORIZED_TRACE_CODE,
        Response::HTTP_NOT_FOUND => self::NOT_FOUND_TRACE_CODE,
        Response::HTTP_METHOD_NOT_ALLOWED => self::METHOD_NOT_ALLOWED_TRACE_CODE,
    ];

    const HTTP_MESSAGES = [
        Response::HTTP_BAD_REQUEST => self::BAD_REQUEST_MESSAGE,
        Response::HTTP_FORBIDDEN => self::ACCESS_DENIED_MESSAGE,
        Response::HTTP_UNAUTHORIZED => self::UNAUTHORIZED_MESSAGE,
        Response::HTTP_NOT_FOUND => self::NOT_FOUND_MESSAGE,
        Response::HTTP_METHOD_NOT_ALLOWED => self::METHOD_NOT_ALLOWED_MESSAGE,
    ];

    /**
     * @param int $httpCode
     *
     * @return string
     */
    public static function getTraceCode(int $httpCode): string
    {
        return self::HTTP_TRACE_CODES[$httpCode] ?? self::DEFAULT_TRACE_CODE;
    }

    /**
     * @param int $httpCode
     *
     * @return string
     */
    public static function getMessage(int $httpCode): string
    {
        return self::HTTP_MESSAGES[$httpCode] ?? self::GENERIC_MESSAGE;
    }

    /**
     * Log the given exception.
     *
     * @param \Throwable      $throwable
     * @param LoggerInterface $logger
     * @param array           $additionalContext
     */
    public static function logException(\Throwable $throwable, LoggerInterface $logger, array $additionalContext = []): void
    {
        $context = array_merge($additionalContext, [
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'trace' => $throwable->getTraceAsString(),
        ]);

        $logger->error($throwable->getMessage(), $context);
    }
}
