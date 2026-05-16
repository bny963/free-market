<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $guarded = [];

    // 出品者へのリレーション
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // いいね（Like）へのリレーション
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    // ログイン中のユーザーがこの商品をいいねしているか判定する補助メソッド
    public function isLikedBy(?User $user): bool
    {
        if (!$user)
            return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}