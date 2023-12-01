<?php

namespace QuetzalStudio\ApiResponse;

class Response
{
    /**
     * Undocumented function
     *
     * @param mixed $resource
     * @param boolean $collection
     * @return QuetzalStudio\ApiResponse\ResourceCollection|QuetzalStudio\ApiResponse\JsonResource
     */
    public static function make($resource = null, $collection = false)
    {
        return $collection ? new ResourceCollection($resource) : new JsonResource($resource);
    }

    public static function setupBodyKeys(array $options)
    {
        Body::$codeKey = data_get($options, 'code', 'code');
        Body::$messageKey = data_get($options, 'message', 'message');
        Body::$dataKey = data_get($options, 'data', 'data');
        Body::$errorsKey = data_get($options, 'errors', 'errors');
    }

    public static function excludeBodyKeys(array $keys)
    {
        Body::$excludeKeys = $keys;
    }
}
