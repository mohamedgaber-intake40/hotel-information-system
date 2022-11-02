<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends BaseApiRequest
{
    const MAX_EMAIL_LENGTH    = 100;
    const MAX_PASSWORD_LENGTH = 50;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => [ 'required', 'email', 'max:' . self::MAX_EMAIL_LENGTH ],
            'password' => [ 'required', 'string', 'max:' . self::MAX_PASSWORD_LENGTH ],
        ];
    }
}
