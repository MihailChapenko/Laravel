<?php

namespace App\Http\Requests;

use App\User;
use http\Env\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EditUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (Auth::id() === 1 || User::role('admin')) ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'editUserName' => 'required|max:255',
            'editUserEmail' => 'required|email|unique:users,email, ' . $this->request->get('userId'),
            'editUserPass' => 'nullable|min:8'
        ];
    }
}
