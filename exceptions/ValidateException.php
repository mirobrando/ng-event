<?php

namespace mirolabs\phalcon\modules\ngEvent\exceptions;

use Phalcon\Exception;

class ValidateException extends Exception
{
    private $errorValidete;

    public function __construct($errorValidate, $code = 0, \Exception $previous = null)
    {
        $this->errorValidete = $errorValidate;
        parent::__construct('conflict in object', $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getErrorValidete()
    {
        return $this->errorValidete;
    }





} 