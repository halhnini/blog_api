<?php
/*
 * This file is part of a blog project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Model;

use OpenApi\Annotations as SWG;

/**
 * Class Error
 */
class Error
{
    /**
     * @var string
     *
     * @SWG\Property(property="traceCode", description="The error trace code.")
     */
    private $traceCode;

    /**
     * @var string
     *
     * @SWG\Property(property="message", description="The error message.")
     */
    private $message;

    /**
     * @var array|null
     *
     * @SWG\Property(
     *     property="traceData",
     *     description="The error trace data.",
     *     type="array",
     *     @SWG\Items(type="object")
     * )
     */
    private $traceData;

    /**
     * DomainError constructor.
     *
     * @param string     $traceCode
     * @param string     $message
     * @param array|null $traceData
     */
    public function __construct(string $traceCode, string $message, ?array $traceData)
    {
        $this->traceCode = $traceCode;
        $this->message = $message;
        $this->traceData = $traceData;
    }

    /**
     * @return string
     */
    public function getTraceCode(): string
    {
        return $this->traceCode;
    }

    /**
     * @param string $traceCode
     *
     * @return $this
     */
    public function setTraceCode(string $traceCode): self
    {
        $this->traceCode = $traceCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getTraceData(): ?array
    {
        return $this->traceData;
    }

    /**
     * @param array|null $traceData
     *
     * @return $this
     */
    public function setTraceData(?array $traceData): self
    {
        $this->traceData = $traceData;

        return $this;
    }
}
