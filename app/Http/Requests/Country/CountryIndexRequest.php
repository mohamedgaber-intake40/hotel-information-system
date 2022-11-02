<?php

namespace App\Http\Requests\Country;

use App\Http\Requests\BaseApiRequest;
use App\Http\Requests\BaseIndexApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class CountryIndexRequest extends BaseIndexApiRequest
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
        return parent::rules();
    }
}
