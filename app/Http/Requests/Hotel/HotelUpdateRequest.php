<?php

namespace App\Http\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class HotelUpdateRequest extends FormRequest
{
    const MIN_NAME_LENGTH = 3;
    const MAX_NAME_LENGTH = 50;

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
            'name'    => [ 'required', 'string', 'unique:hotels,name,'. $this->hotel->id, 'min:' . self::MIN_NAME_LENGTH, 'max:' . self::MAX_NAME_LENGTH ],
            'city_id' => [ 'required', 'exists:cities,id' ]
        ];
    }
}
