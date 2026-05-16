<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // コントローラー側でauth制限するためtrue
    }

    public function rules(): array
    {
        return [
            'item_img' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // FN029
            'categories' => ['required', 'array', 'min:1'], // 複数選択（FN028-1-2）
            'condition' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'item_img.required' => '商品画像を選択してください。',
            'categories.required' => 'カテゴリーを1つ以上選択してください。',
            'condition.required' => '商品の状態を選択してください。',
            'name.required' => '商品名を入力してください。',
            'description.required' => '商品の説明を入力してください。',
            'price.required' => '販売価格を入力してください。',
            'price.integer' => '販売価格は数値で入力してください。',
        ];
    }
}