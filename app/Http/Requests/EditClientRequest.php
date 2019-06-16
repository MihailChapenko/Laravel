<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class EditClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (Auth::id() === 1 && User::role('super-admin')) ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'editClientName' => 'required|max:255',
            'editClientModel' => 'required|max:10',
            'editClientTheme' => 'required|max:30',
            'editClientValueTable' => 'required|max:30',
        ];
    }
}
