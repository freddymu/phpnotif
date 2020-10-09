<?php


namespace Freddymu\Phpnotif\Entities;


class GenericResponseEntity
{
    /**
     * @var bool
     */
    public $success = false;
    /**
     * @var string|null
     */
    public $message = null;
    /**
     * @var object|null
     */
    public $data = null;
    /**
     * Contain data that may be used for next sequence or process
     * @var null
     */
    public $carry = null;
    /**
     * @var array|null
     */
    public $errors = null;

    /**
     * @var null
     */
    public $processTime = null;

    /**
     * @var float|string
     */
    private $timeStart = null;

    /**
     * GenericResponseEntity constructor.
     */
    public function __construct()
    {
        $this->timeStart = microtime(true);
    }

    /**
     * @return string
     */
    final public function getProcessTime(): string
    {
        $timeEnd = microtime(true);

        return round($this->processTime ?? ($timeEnd - $this->timeStart), 6) . ' second(s)';
    }

    /**
     * @return bool
     */
    final public function isFailed(): bool
    {
        return $this->success === false;
    }

    /**
     * @return bool
     */
    final public function isSucceed(): bool
    {
        return $this->success === true;
    }
}
