<?php

namespace Defrindr\Crudify\Exceptions;

use Defrindr\Crudify\Helpers\ResponseHelper;
use Exception;
use Illuminate\Validation\Validator;

class ValidationException extends Exception
{
  protected Validator $validator;

  public function __construct(Validator $validator)
  {
    $this->validator = $validator;
  }


  public function render()
  {
    // return a json with desired format
    return ResponseHelper::validationError($this->validator?->errors());
  }
}
