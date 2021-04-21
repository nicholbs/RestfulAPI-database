<?php


/**
 * Class APIException an exception class thrown whenever the request could not be successfully handled by the API.
 * @author Rune Hjelsvol, Nicholas Sellevåg
 */
class APIException extends Exception
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
        $this->except = "API Exception";
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