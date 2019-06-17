<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->request->get('userId') == Auth::id()) {
            return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'userId' => 'required',
            'firstName' => 'nullable|max:255',
            'lastName' => 'nullable|max:255',
            'address' => 'nullable|max:255',
            'phone' => 'nullable|max:10',
            'email' => 'required|email|unique:users,email, ' . $this->request->get('userId'),
        ];
    }
}
