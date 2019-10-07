<?php

namespace Yaro\Jarboe\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        $model = config('jarboe.admin_panel.admin_model');

        return [
            'name'     => 'required',
            'email'    => sprintf('required|email|unique:%s,email', (new $model)->getTable()),
            'password' => 'required|confirmed',
        ];
    }
}
