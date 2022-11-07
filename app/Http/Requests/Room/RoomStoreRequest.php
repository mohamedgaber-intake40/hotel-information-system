<?php

namespace App\Http\Requests\Room;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class RoomStoreRequest extends BaseApiRequest
{
    const MIN_NUMBER = 1;
    const MAX_NUMBER = 99999;

    const MIN_PRICE = 1;
    const MAX_PRICE = 99999;

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
            'number'          => [ 'required', 'integer', 'unique:rooms', 'min:' . self::MIN_NUMBER , 'max:' . self::MAX_NUMBER ],
            'price_per_night' => [ 'required', 'numeric', 'min:' . self::MIN_PRICE, 'max:' . self::MAX_PRICE ],
            'facilities'      => [ 'required', 'array' ],
            'facilities.*'    => [ 'required', 'exists:facilities,id','distinct' ]
        ];
    }
}
