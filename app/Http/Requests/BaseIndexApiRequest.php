<?php


namespace App\Http\Requests;


class BaseIndexApiRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'per_page' => [ 'sometimes', 'integer', 'min:1', 'max:50' ]
        ];
    }
}
