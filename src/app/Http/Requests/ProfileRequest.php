<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'profile_image.image' => 'プロフィール画像は画像ファイルを選択してください。',
            'profile_image.mimes' => 'プロフィール画像はjpeg、png、jpg形式を選択してください。',
            'profile_image.max' => 'プロフィール画像は2MB以内で選択してください。',

            'name.required' => 'ユーザー名を入力してください。',
            'name.string' => 'ユーザー名は文字列で入力してください。',
            'name.max' => 'ユーザー名は255文字以内で入力してください。',

            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex' => '郵便番号は123-4567の形式で入力してください。',

            'address.required' => '住所を入力してください。',
            'address.string' => '住所は文字列で入力してください。',
            'address.max' => '住所は255文字以内で入力してください。',

            'building.string' => '建物名は文字列で入力してください。',
            'building.max' => '建物名は255文字以内で入力してください。',
        ];
    }
}