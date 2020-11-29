<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'bio' => 'required|string|max:255',
            'password' => 'required|confirmed|min:3|max:16',
            'profile_picture' => 'required',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:25'
        ];
    }
}
