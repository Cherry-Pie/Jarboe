<?php

namespace Yaro\Jarboe\Http\Requests\Admins;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'avatar.*.file' => 'mimes:jpeg,bmp,png,gif',
            'name' => 'required',
            'email' => 'required|email|unique:admins,email,'. $this->route('id'),
            'password' => 'confirmed',
        ];
    }
}
