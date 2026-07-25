<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $user = $this->user();

        $this->merge([
            'postal_code' => $user ? $user->postal_code : null,
            'address' => $user ? $user->address : null,
        ]);
    }

    public function rules()
    {
        return [
            'payment_method' => ['required'],
            'address' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください。',
            'address.required' => '配送先住所を登録してください。',
        ];
    }
}
