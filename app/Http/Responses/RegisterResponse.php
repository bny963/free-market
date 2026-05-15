<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * 会員登録成功後のリダイレクト先をプロフィール画面に指定（FN006）
     */
    public function toResponse($request)
    {
        // プロフィール設定画面（/profile）へ遷移
        return redirect('/profile');
    }
}