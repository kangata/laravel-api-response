<?php

namespace QuetzalStudio\ApiResponse\Concerns;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use QuetzalStudio\ApiResponse\Body;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

trait CustomFailedValidation
{
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $body = Body::errors($validator->errors()->toArray())->unprocessableEntity();

        $response = apiResponse($body)->status(HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY);

        throw new HttpResponseException($response);
    }
}
