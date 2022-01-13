<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Exception;

use \Exception;
use Throwable;

/**
 * Class BlogException
 */
class BlogException extends Exception
{
    const DEFAULT_ERROR_MESSAGE = 'An error occurred during execution, please contact the support !';
    const DEFAULT_ERROR_CODE = 'error_general';

    /**
     * define if the exception type message must be rendered or not.
     * if your exception type message must be rendered, you should override this static property.
     * for example :
     * <code>
     *     class MyException extend BlogException
     *     {
     *         protected static $rendered = true;
     *     }
     * </code>
     *
     * @var bool
     */
    protected static $rendered = false;

    /**
     * define if the exception type must be logged.
     * if your exception type message must not be logged, you should override this static property.
     * for example :
     * <code>
     *     class MyException extend BlogException
     *     {
     *         protected static $loggable = false;
     *     }
     * </code>
     *
     * @var bool
     */
    protected static $logged = true;

    /**
     * @var string
     */
    protected $renderedCode;

    /**
     * BlogException constructor.
     *
     * @param string         $message
     * @param string         $renderedCode
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', string $renderedCode = self::DEFAULT_ERROR_CODE, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->renderedCode = $renderedCode;
    }

    /**
     * Check if the exception type message must be rendered or not.
     *
     * @return bool
     */
    public function isRendered(): bool
    {
        return static::$rendered;
    }

    /**
     * Check if the exception type must be logged.
     *
     * @return bool
     */
    public function isLogged(): bool
    {
        return static::$logged;
    }

    /**
     * Return the error message corresponds to error code.
     *
     * @param string $errorCode
     *
     * @return string
     */
    public static function getErrorMessage(string $errorCode): string
    {
        if (!defined('static::ERRORS_MESSAGES')
            || !is_array(static::ERRORS_MESSAGES)
            || !array_key_exists($errorCode, static::ERRORS_MESSAGES)
            || !is_string(static::ERRORS_MESSAGES[$errorCode])
        ) {
            return self::DEFAULT_ERROR_MESSAGE;
        }

        return static::ERRORS_MESSAGES[$errorCode];
    }

    /**
     * @return string
     */
    public function getRenderedCode(): string
    {
        return $this->renderedCode;
    }
}
