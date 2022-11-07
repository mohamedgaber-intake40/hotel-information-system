<?php

namespace App\Http\Requests\Country;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class CountryStoreRequest extends BaseApiRequest
{
    const MAX_NAME_LENGTH = 50;
    const MIN_NAME_LENGTH = 3;
    const MAX_ISO_LENGTH  = 5;
    const MIN_ISO_LENGTH  = 2;

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
            'name'     => [ 'required', 'unique:countries','string','max:' . self::MAX_NAME_LENGTH,'min:' . self::MIN_NAME_LENGTH  ],
            'iso_code' => [ 'required', 'unique:countries','string','max:' . self::MAX_ISO_LENGTH,'min:' . self::MIN_ISO_LENGTH  ]
        ];
    }
}
