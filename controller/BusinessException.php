<?php


/**
 * Class BusinessException is an unusual condition within a business process that warrants investigation or external review.
 * @author Nicholas Sellevåg - Inspiration from Rune Hjelsvol
 */
class BusinessException extends Exception
{
    /**
     * @var string $reason for why the URI could not be successfully handled.
     */
    protected $reason;
    /**
     * @var int $detailCode of web request
     */

    protected $detailCode;

    /**
     * @var string $exception type
     */
    protected $except;


    public function __construct(int $code, string $reason)
    {
        $this->detailCode = $code;
        $this->reason = $reason;
        $this->except = "Business Exception";
    }

    public function getReason() {
        return $this->reason;
    }

    public function getDetailCode()
    {
        return $this->detailCode;
    }
    public function getExcept()
    {
        return $this->except;
    }
}