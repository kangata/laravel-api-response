<?php

namespace QuetzalStudio\ApiResponse;

use BadMethodCallException;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Body
{
    public static string $codeKey = 'code';

    public static string $messageKey = 'message';

    public static string $dataKey = 'data';

    public static string $errorsKey = 'errors';

    public static array $excludeKeys = [];

    protected $code;

    protected $message;

    protected $data;

    protected array $errors;

    public function __construct($code = null, $message = null, $data = null, array $errors = [])
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
        $this->errors = $errors;
    }

    public static function make($code = null, $message = null, $data = null, array $errors = [])
    {
        return new static($code, $message, $data, $errors);
    }

    protected function time()
    {
        return round((microtime(true) - LARAVEL_START) * 1000);
    }

    public function except(array $keys): self
    {
        static::$excludeKeys = array_merge(static::$excludeKeys, $keys);

        return $this;
    }

    public function toArray()
    {
        $data = [
            'time' => static::time(),
            static::$codeKey => $this->code,
            static::$messageKey => $this->message,
            static::$dataKey => $this->data,
        ];

        if (! empty($this->errors)) {
            $data['errors'] = $this->errors;
        }

        return Arr::except($data, static::$excludeKeys);
    }

    public function handlePropertyMethod($method, $arguments)
    {
        if (empty($arguments)) {
            return $this->{$method};
        }

        $this->{$method} = $arguments[0];

        return $this;
    }

    public function getStatusText(int $code)
    {
        return Response::$statusTexts[$code] ?? 'Unknown';
    }

    public function makeStatusMessage(int $code)
    {
        return [
            static::$codeKey => $code,
            static::$messageKey => $this->getStatusText($code),
        ];
    }

    public function getStatusMessage($method)
    {
        $items = [
            'success' => [
                static::$codeKey => Response::HTTP_OK,
                static::$messageKey => 'Successful',
            ],
            'somethingWentWrong' => [
                static::$codeKey => Response::HTTP_SERVICE_UNAVAILABLE,
                static::$messageKey => 'Something went wrong',
            ],
            'ok' => $this->makeStatusMessage(Response::HTTP_OK),
            'created' => $this->makeStatusMessage(Response::HTTP_CREATED) ,
            'badRequest' => $this->makeStatusMessage(Response::HTTP_BAD_REQUEST),
            'unauthorized' => $this->makeStatusMessage(Response::HTTP_UNAUTHORIZED),
            'forbidden' => $this->makeStatusMessage(Response::HTTP_FORBIDDEN),
            'notFound' => $this->makeStatusMessage(Response::HTTP_NOT_FOUND),
            'unprocessableEntity' => $this->makeStatusMessage(Response::HTTP_UNPROCESSABLE_ENTITY),
            'serverError' => $this->makeStatusMessage(Response::HTTP_INTERNAL_SERVER_ERROR),
            'serviceUnavailable' => $this->makeStatusMessage(Response::HTTP_SERVICE_UNAVAILABLE),
        ];

        $this->code($items[$method][static::$codeKey]);
        $this->message($items[$method][static::$messageKey]);

        return $this;
    }

    public function __call($method, $arguments)
    {
        $properties = ['code', 'message', 'data', 'errors'];

        if (in_array($method, $properties)) {
            return $this->handlePropertyMethod($method, $arguments);
        }

        try {
            return $this->getStatusMessage($method, $arguments);
        } catch (Throwable $e) {
            throw new BadMethodCallException("Undefined method {$method}");
        }
    }

    public static function __callStatic($method, $arguments)
    {
        $properties = ['data', 'errors'];

        $self = new static();

        if (in_array($method, $properties)) {
            return $self->handlePropertyMethod($method, $arguments);
        }

        return $self->getStatusMessage($method, $arguments);
    }
}
