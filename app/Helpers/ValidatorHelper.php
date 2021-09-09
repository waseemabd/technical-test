<?php


namespace App\Helpers;


class ValidatorHelper
{

    public static function messages() {
            $messages = [
                'required' => 'The :attribute field is required.',
            ];
        return $messages;
    }

}
