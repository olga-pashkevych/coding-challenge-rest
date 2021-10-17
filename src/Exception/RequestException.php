<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RequestException extends HttpException
{
    public function __construct(array $messages)
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, implode('; ', $messages));
    }
}