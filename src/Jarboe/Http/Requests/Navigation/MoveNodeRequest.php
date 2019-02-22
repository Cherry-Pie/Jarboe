<?php

namespace Yaro\Jarboe\Http\Requests\Navigation;


use Illuminate\Foundation\Http\FormRequest;

class MoveNodeRequest extends FormRequest
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
            'id' => 'required',
            'root_id' => '',
            'left_id' => '',
            'right_id' => '',
        ];
    }

}