<?php

namespace App\Exceptions;

use Exception;

class OrderNotConfirmedException extends Exception
{
    protected $message = 'Order must be confirmed before processing payment';
    protected $code = 403;
}
