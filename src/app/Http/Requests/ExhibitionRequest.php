<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['exists:categories,id'],
            'condition_id' => ['required', 'exists:conditions,id'],
            'name' => ['required', 'string', 'max:255'],
            'brand_name' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages()
    {
        return [
            'image.required' => '商品画像を選択してください。',
            'image.image' => '商品画像は画像ファイルを選択してください。',
            'image.mimes' => '商品画像はjpeg、png、jpg形式で選択してください。',
            'image.max' => '商品画像は2MB以内で選択してください。',

            'category_ids.required' => 'カテゴリーを選択してください。',
            'category_ids.array' => 'カテゴリーの選択形式が正しくありません。',
            'category_ids.*.exists' => '選択されたカテゴリーが正しくありません。',

            'condition_id.required' => '商品の状態を選択してください。',
            'condition_id.exists' => '選択された商品の状態がありません。',

            'name.required' => '商品名を入力してください。',
            'name.string' => '商品名は文字列で入力してください。',
            'name.max' => '商品名は255文字以内で入力してください。',

            'brand_name.string' => 'ブランド名は文字列で入力してください。',
            'brand_name.max' => 'ブランド名は255文字以内で入力してください。',

            'description.required' => '商品の説明を入力してください。',
            'description.string' => '商品の説明は文字列で入力してください。',
            'description.max' => '商品の説明は255文字以内で入力してください。',

            'price.required' => '販売価格を入力してください。',
            'price.integer' => '販売価格は整数で入力してください。',
            'price.min' => '販売価格は1円以上で入力してください。',
        ];
    }
}