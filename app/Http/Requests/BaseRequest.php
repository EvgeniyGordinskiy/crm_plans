<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use App\Traits\Restable;

abstract class BaseRequest extends FormRequest
{
    use Restable;

    /**
     * Custom message for forbidden requests.
     */
    protected $forbidden_message = 'User not authorized to access resource';

    /**
     * Internal code to be displayed.
     */
    protected $forbidden_code = 40110;

    /**
     * Get the response for a forbidden operation.
     *
     * @return Response
     */
    public function forbiddenResponse()
    {
        return $this->respondUnauthorized(
            $this->forbidden_message,
            $this->forbidden_code
        );
    }

    protected function sanitizeString(string $property_name): string
    {
        return filter_var($this->input($property_name) ,FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
    }
}
