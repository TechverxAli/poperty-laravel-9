<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MainOwnerKeyExist implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach($value as $item) {
            if (!array_key_exists('main_owner', $item)) {
                return false;
            }
            if(!in_array($item['main_owner'],[0,1])){
                return  false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The main owner is required and must be bollen type.';
    }
}
