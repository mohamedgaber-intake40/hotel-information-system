<?php

namespace App\Http\Requests\Reservation;

use App\Http\Requests\BaseIndexApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationIndexRequest extends BaseIndexApiRequest
{

    const ALLOWED_SORTS_MAPPING = [
        'hotel_name' => 'hotel_name',
        'country'    => 'country_name',
        'city'       => 'city_name',
        'price'      => 'price_per_night'
    ];

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
        return array_merge([
                               'sort_by' => [ 'sometimes', Rule::in(array_keys(self::ALLOWED_SORTS_MAPPING)) ]
                           ], parent::rules());
    }

    public static function getSortColumn($value)
    {
        return self::ALLOWED_SORTS_MAPPING[ $value ] ?? null;
    }

    public function withValidator(Validator $validator)
    {
        if ( $validator->fails() || !$this->has('sort_by') ) return;
        $this->merge([
                         'sort_by' => static::getSortColumn($this->sort_by),
                         'sort_direction' => $this->sort_direction ?: 'asc'
                     ]);
    }
}
