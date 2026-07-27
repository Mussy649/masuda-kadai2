<?php

namespace App\Http\Requests;

use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;

class LoginRequest extends FortifyLoginRequest
{
    public function rules()
    {
        return [
            Fortify::username() => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            Fortify::username() . '.required'
                => 'メールアドレスを入力してください。',
            Fortify::username() . '.string'
                => 'メールアドレスは文字列で入力してください。',
            Fortify::username() . '.email'
                => 'メールアドレスはメール形式で入力してください。',

            'password.required' => 'パスワードを入力してください。',
            'password.string' => 'パスワードは文字列で入力してください。',
        ];
    }
}