<?php

use QuetzalStudio\ApiResponse\Body;
use QuetzalStudio\ApiResponse\Response;

if (! function_exists('apiResponse')) {
    function apiResponse(Body $body = null)
    {
        return new Response($body);
    }
}

if (! function_exists('makeResponseBody')) {
    function makeResponseBody($code = null, $message = null, $data = null, array $errors = [])
    {
        return new Body($code, $message, $data, $errors);
    }
}
