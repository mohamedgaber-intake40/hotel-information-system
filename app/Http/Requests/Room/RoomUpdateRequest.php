<?php

namespace App\Http\Requests\Room;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class RoomUpdateRequest extends BaseApiRequest
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
            'number'          => [ 'required', 'integer', 'unique:rooms,number,'. $this->room, 'min:1', 'max:99999' ],
            'price_per_night' => [ 'required', 'numeric', 'min:1', 'max:99999' ],
            'facilities'      => [ 'required', 'array' ],
            'facilities.*'    => [ 'required', 'exists:facilities,id','distinct' ]
        ];
    }
}
