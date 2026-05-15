<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // 1. RegisterRequestのインスタンスを手動で作ってバリデーションを実行
        $request = new RegisterRequest();

        // Fortifyの引数はarrayなので、Validatorを直接呼び出す形式にする
        \Validator::make($input, $request->rules(), $request->messages())->validate();

        // 2. ユーザー作成
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}