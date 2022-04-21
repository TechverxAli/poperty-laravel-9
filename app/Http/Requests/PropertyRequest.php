<?php

namespace App\Http\Requests;

use App\Rules\MainOwnerKeyExist;
use App\Rules\MainOwnerExist;
use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest
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
            'house_name_number' => 'required|string',
            'postcode' => 'required|string',
            'owners' => ['required', new MainOwnerKeyExist(), new MainOwnerExist()],
            'owners.*.user_id' => 'required',
        ];
    }
}
