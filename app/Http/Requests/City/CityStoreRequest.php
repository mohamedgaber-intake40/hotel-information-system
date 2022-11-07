<?php

namespace App\Http\Requests\City;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class CityStoreRequest extends BaseApiRequest
{
    const MAX_NAME_LENGTH = 50;
    const MIN_NAME_LENGTH  = 3;

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
            'name'       => [ 'required', 'unique:cities','string','max:' . self::MAX_NAME_LENGTH,'min:' . self::MIN_NAME_LENGTH ],
            'country_id' => [ 'required', 'exists:countries,id' ]
        ];
    }
}
