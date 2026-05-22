<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'brand',
        'price',
        'description',
        'condition',
        'img_url',
        'is_sold'
    ];
    protected $casts = [
        'is_sold' => 'boolean',
    ];
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

    // カテゴリ（多対多）
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // コメント（1対多）
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}