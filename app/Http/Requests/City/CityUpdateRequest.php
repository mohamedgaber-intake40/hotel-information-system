<?php

namespace App\Http\Requests\City;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class CityUpdateRequest extends BaseApiRequest
{
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
            'name'       => [ 'required', 'unique:cities,name,' . $this->city->id ],
            'country_id' => [ 'required', 'exists:countries,id' ]
        ];
    }
}
