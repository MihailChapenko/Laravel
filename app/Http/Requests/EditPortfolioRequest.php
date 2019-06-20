<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EditPortfolioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (Auth::user()->hasPermissionTo('crud portfolios')) ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'editPortfolioName' => 'required|max:200',
            'editPortfolioDescription' => 'required|max:255',
            'editPortfolioCurrency' => 'required|max:3',
            'editPortfolioAllocationMax' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'editPortfolioAllocationMin' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'editPortfolioSortOrder' => 'required|integer',
        ];
    }
}
