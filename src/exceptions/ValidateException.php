<?php

namespace mirolabs\phalcon\modules\ngEvent\exceptions;

use Phalcon\Exception;

class ValidateException extends Exception
{
    private $errorValidate;

    public function __construct($errorValidate, $code = 0, \Exception $previous = null)
    {
        $this->errorValidate = $errorValidate;
        parent::__construct('conflict in object', $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getErrorValidate()
    {
        return $this->errorValidate;
    }





} 