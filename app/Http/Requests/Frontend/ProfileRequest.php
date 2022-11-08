<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            ['first_name'    => 'required|string|max:255'],
            ['last_name'    => 'required|string|max:255'],
            ['email'    => 'required|string|email|unique:users,' . auth()->id()],
            ['mobile'    => 'required|numeric|unique:users,' . auth()->id()],
            ['password'  => 'nullable|string|confirmed|min:8'],
            ['user_image'    => 'nullable|file|mimes:png,jpg,jpeg,svg|max:20000'],
        ];
    }
}
