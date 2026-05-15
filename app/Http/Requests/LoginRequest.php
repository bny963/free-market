<?php

namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\LoginRequest as FortifyBaseRequest;

class LoginRequest extends FortifyBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルール（FN009）
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'], 
            'password' => ['required', 'string'],
        ];
    }

    /**
     * エラーメッセージのカスタマイズ（FN010-1 遵守）
     */
    public function messages(): array
    {
        return [
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスはメール形式で入力してください', 
            'password.required' => 'パスワードを入力してください',
        ];
    }
}