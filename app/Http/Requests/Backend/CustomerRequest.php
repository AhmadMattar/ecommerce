<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

use function GuzzleHttp\default_user_agent;

class CustomerRequest extends FormRequest
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
        switch($this->method())
        {
            case 'POST':
            {
                return[
                    'first_name'         => 'required',
                    'last_name'          => 'required',
                    'username'           => 'required|max:20|unique:users',
                    'email'              => 'required|email|max:255|unique:users',
                    'mobile'             => 'required|numeric|unique:users',
                    'password'           => 'required|min:8',
                    'status'             => 'required',
                    'user_image'         => 'nullable|mimes:png,jpg,jpeg,svg|max:2000',
                ];
            }
            case 'PUT':
            {
                return[
                    'first_name'         => 'required',
                    'last_name'          => 'required',
                    'username'           => 'required|max:20|unique:users,username,'.$this->route()->customer->id,
                    'email'              => 'required|email|unique:users,email,'.$this->route()->customer->id,
                    'mobile'             => 'required|numeric|unique:users,mobile,'.$this->route()->customer->id,
                    'password'           => 'required|min:8',
                    'status'             => 'required',
                    'user_image'         => 'nullable|mimes:png,jpg,jpeg,svg|max:2000',
                ];
            }
            case 'PATCH':
            default: break;
        }
    }
}
