<?php

namespace App\Http\Requests;

use App\User;
use const http\Client\Curl\AUTH_ANY;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (Auth::user()->hasPermissionTo('crud clients')) ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'adminId' => 'required|unique:clients,admin_id|exists:users,id',
            'portfolioId' => 'required',
            'clientName' => 'required|max:255',
            'clientModel' => 'required|max:10',
            'clientTheme' => 'required|max:30',
            'clientValueTable' => 'required|max:30',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'clientName.required' => 'Name is required!',
            'clientModel.required' => 'Model is required!',
            'clientTheme.required' => 'Theme is required!',
            'clientValueTable.required' => 'Value is required!'
        ];
    }
}
