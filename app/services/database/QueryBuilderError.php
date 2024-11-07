<?php

namespace App\Services\Database;

class QueryBuilderError extends \Exception
{
    private $error;

    function __construct($error)
    {
        parent::__construct();

        $this->error = $error;
    }

    public function error()
    {
        return $this->error;
    }

    public function getErrorCode()
    {
        return $this->error['code'];
    }

    public function getErrorMessage()
    {
        return $this->error['message'];
    }
}
