<?php

use QuetzalStudio\ApiResponse\Body;
use QuetzalStudio\ApiResponse\ResourceCollection;
use QuetzalStudio\ApiResponse\Response;

if (! function_exists('api_response')) {
    function api_response($resource = null, $collection = false)
    {
        return Response::make($resource, $collection);
    }
}

if (! function_exists('make_body')) {
    function make_body($code = null, $message = null, $data = null, array $errors = [])
    {
        return new Body($code, $message, $data, $errors);
    }
}
