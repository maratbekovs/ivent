<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetUserHistory extends Model
{
    use HasFactory;

    protected $table = 'asset_user_history'; // Указываем имя таблицы явно, если оно отличается от AssetUserHistories

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'asset_id',
        'user_id',
        'assigned_at',
        'unassigned_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'assigned_at' => 'datetime',
        'unassigned_at' => 'datetime',
    ];

    /**
     * Получить актив, к которому относится запись истории.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Получить пользователя, к которому относится запись истории.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
