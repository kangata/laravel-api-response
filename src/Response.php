<?php

namespace QuetzalStudio\ApiResponse;

use Illuminate\Http\Resources\Json\JsonResource;

class Response extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = null;

    public function status($code)
    {
        return $this->response()->setStatusCode($code);
    }

    public function toArray($request)
    {
        return $this->resource->toArray();
    }

    public static function setupBodyKeys(array $options)
    {
        Body::$codeKey = data_get($options, 'code', 'code');
        Body::$messageKey = data_get($options, 'message', 'message');
        Body::$dataKey = data_get($options, 'data', 'data');
        Body::$errorsKey = data_get($options, 'errors', 'errors');
    }
}
