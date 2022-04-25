<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ProductReviewRequest extends FormRequest
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
        switch($this->method()){
            case 'POST':
            {
                //
            }

            case 'PUT':
            {
                return [
                    'name'                  => 'required|max:255',
                    'user_id'               => 'nullable',
                    'product_id'            => 'required',
                    'email'                 => 'required|email',
                    'rating'                => 'required|numeric',
                    'status'                => 'required',
                    'title'                 => 'required',
                    'message'               => 'required',
                ];
            }

            case 'PATCH':
            default: break;

        }
    }
}
