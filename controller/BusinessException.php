<?php


/**
 * Class BusinessException an exception class thrown whenever the database request was flawed.
 * @author Rune Hjelsvol, Nicholas Sellevåg
 */
class BusinessException extends Exception
{
    /**
     * @var string $reason for why the URI could not be successfully handled.
     */
    protected $reason;
    /**
     * @var string $detailCode of web request
     */
    protected $detailCode;
    /**
     * @var string $detailCode of web request
     */
    protected $except;


    public function __construct($code, $reason)
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