<?php

namespace QuetzalStudio\ApiResponse;

class Body
{
    public static string $codeKey = 'code';

    public static string $messageKey = 'message';

    public static string $dataKey = 'data';

    public static string $errorsKey = 'errors';

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

    protected function time()
    {
        return round((microtime(true) - LARAVEL_START) * 1000);
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

        return $data;
    }

    public static function make($code = null, $message = null, $data = null, array $errors = [])
    {
        return new static($code, $message, $data, $errors);
    }

    public function __call($method, $arguments)
    {
        $properties = ['code', 'message', 'data', 'errors'];

        if (in_array($method, $properties)) {
            return $this->handlePropertyMethod($method, $arguments);
        }

        return $this->getStatusMessage($method, $arguments);
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

    public function handlePropertyMethod($method, $arguments)
    {
        if (empty($arguments)) {
            return $this->{$method};
        }

        $this->{$method} = $arguments[0];

        return $this;
    }

    public function getStatusMessage($method)
    {
        $items = [
            'success' => [
                static::$codeKey => 200,
                static::$messageKey => 'Successful.',
            ],
            'created' => [
                static::$codeKey => 201,
                static::$messageKey => 'Successful.',
            ],
            'badRequest' => [
                static::$codeKey => 400,
                static::$messageKey => 'Bad request.',
            ],
            'unauthorized' => [
                static::$codeKey => 401,
                static::$messageKey => 'Unauthorized.',
            ],
            'forbidden' => [
                static::$codeKey => 403,
                static::$messageKey => 'Forbidden.',
            ],
            'notFound' => [
                static::$codeKey => 404,
                static::$messageKey => 'Not found.',
            ],
            'unprocessableEntity' => [
                static::$codeKey => 422,
                static::$messageKey => 'Unprocessable entity.',
            ],
            'serverError' => [
                static::$codeKey => 500,
                static::$messageKey => 'Server error.',
            ],
            'serviceUnavailable' => [
                static::$codeKey => 503,
                static::$messageKey => 'Oops! Something went wrong, please try again later.',
            ],
        ];

        $this->code($items[$method][static::$codeKey]);
        $this->message($items[$method][static::$messageKey]);

        return $this;
    }
}
