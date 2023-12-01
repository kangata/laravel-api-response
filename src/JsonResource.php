<?php

namespace QuetzalStudio\ApiResponse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as Resource;

class JsonResource extends Resource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = null;

    protected ?Body $body = null;

    public function __construct($resource)
    {
        if (! $resource instanceof Body) {
            $this->wrap(Body::$dataKey);

            $this->body = Body::success();
        }

        parent::__construct($resource);
    }

    public function status($code)
    {
        return $this->response()->setStatusCode($code);
    }

    public function toArray($request)
    {
        $this->body?->except([Body::$dataKey]);

        return parent::toArray($request);
    }

    /**
     * Get any additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with(Request $request)
    {
        if (! $this->resource instanceof Body) {
            return $this->body?->toArray() ?? [];
        }

        return [];
    }

    public function withBody(Body|array $payload): self
    {
        $data = $payload instanceof Body ? $payload->data() : data_get($payload, 'data');

        $this->resource = $data ?? $this->resource;

        $this->body = $payload instanceof Body
            ? $payload
            : Body::make(
                code: data_get($payload, 'code'),
                message: data_get($payload, 'message'),
                errors: data_get($payload, 'errors', []),
            );

        return $this;
    }

    public function __call($method, $parameters)
    {

    }
}
