<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],   
            'date' => ['required', 'date'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'payer_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}