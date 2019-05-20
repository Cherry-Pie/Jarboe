<?php

namespace Yaro\Jarboe\Http\Requests\Auth;


use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        $otpData = [];
        if (config('jarboe.admin_panel.two_factor_auth.enabled')) {
            $otpData['otp'] = 'required';
        }

        return [
            'email'    => 'required|email',
            'password' => 'required',
            'remember' => '',
        ];
    }

}