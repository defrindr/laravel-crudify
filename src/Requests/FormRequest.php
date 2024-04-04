<?php

namespace Defrindr\Crudify\Requests;

use Defrindr\Crudify\Helpers\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Validation\ValidationException;

class FormRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Write custom error response for API
     *
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $response = ResponseHelper::validationError($validator?->errors());

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
