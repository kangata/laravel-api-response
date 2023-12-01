# Laravel API Response


## Installation
```composer require quetzal-studio/laravel-api-response```


## Usage

### With array
```php
$user = [
    'id' => 1,
    'name' => 'Ata',
];

return api_response($user);

// result
{
  "data": {
    "id": 1,
    "name": "Ata"
  },
  "time": 4,
  "code": 200,
  "message": "Successful"
}
```

### With model
```php
$user = Model::first();

return api_response($user);

// result
{
  "data": {
    "id": 1,
    "name": "Ata"
  },
  "time": 4,
  "code": 200,
  "message": "Successful"
}
```

### With body

#### User body class
```php
use QuetzalStudio\ApiResponse\Body;

$body = Body::make(code: 404, message: 'Not Found');

return api_response($body)->status(404);

// result
{
  "time": 6,
  "code": 404,
  "message": "Not Found",
  "data": null
}
```

#### Use make_body function
```php
return api_response(
    make_body(code: 404, message: 'Not Found')
)->status(404);

// result
{
  "time": 6,
  "code": 404,
  "message": "Not Found",
  "data": null
}
```

#### Use withBody method
```php
return api_response()->withBody([
    'code' => 403,
    'message' => 'Forbidden',
    'data' => [
        'ip' => '127.0.0.1',
    ],
])->status(403);

// result
{
  "data": {
    "ip": "127.0.0.1"
  },
  "time": 6,
  "code": 403,
  "message": "Forbidden"
}
```

## Change response keys
```php
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::setupBodyKeys([
            'code' => 'status_code',
            'message' => 'status_message',
            'data' => 'result',
        ]);
    }
```

## Exclude some keys
```php
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::excludeBodyKeys(['time', 'code']);
    }
```
