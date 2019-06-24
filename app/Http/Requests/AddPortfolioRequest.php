<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddPortfolioRequest extends FormRequest
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
            'portfolioParentId' => 'required|integer',
            'portfolioParentName' => 'required|max:200',
            'portfolioName' => 'required|max:200',
            'portfolioDescription' => 'required|max:255',
            'portfolioCurrency' => 'required|max:3',
            'portfolioAllocationMax' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'portfolioAllocationMin' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'portfolioSortOrder' => 'required|integer',
        ];
    }
}
