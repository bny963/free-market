<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 誰でも（コントローラー側でauth制限するためtrue）
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:255'], // 入力必須、最大255文字
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => '商品コメントを入力してください',
            'content.max' => 'コメントは255文字以内で入力してください',
        ];
    }
}