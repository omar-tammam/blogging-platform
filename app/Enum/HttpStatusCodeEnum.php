<?php

namespace App\Enum;

class HttpStatusCodeEnum extends Enum
{
    const OK = 200;
    const CREATED = 201;
    const UNAUTHORIZED = 401;
    const NOT_FOUND = 404;
    const FORBIDDEN = 403;
    const BAD_REQUEST = 400;
    const UNPROCESSABLE_ENTITY = 422;
    const TOO_MANY_REQUESTS = 429;
    const CONFLICT = 409;
    const INTERNAL_SERVER_ERROR = 500;
    const PERMANENT_REDIRECT = 308;
}
