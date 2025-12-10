<?php

namespace App\Exceptions;

use Exception;

class PaymentGatewayNotFoundException extends Exception
{
    protected $message = 'Payment gateway not found';
    protected $code = 404;
}
