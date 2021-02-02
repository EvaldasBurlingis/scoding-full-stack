<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'required',
            'email'    => 'email|unique:users',
            'password' => 'required|confirmed|min:8|max:32'
        ];
    }

    /**
     * Custom message for validation
     *
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'email.email' => 'Invalid email address.',
            'email.unique' => 'Email is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be between 8-32 characters.',
            'password.max' => 'Password must be between 8-32 characters.',
        ];
    }
}
