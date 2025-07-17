<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Asset extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'serial_number',
        'inventory_number',
        'mac_address',
        'qr_code_data',
        'category_id',
        'asset_status_id',
        'room_id',
        'current_user_id',
        'purchase_year',
        'warranty_expires_at',
        'notes',
    ];

    protected $casts = [
        'warranty_expires_at' => 'datetime',
    ];

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'asset_document');
    }
    
    /**
     * Перемещения, в которых участвовал этот актив.
     */
    public function stockMovements(): BelongsToMany
    {
        return $this->belongsToMany(StockMovement::class, 'asset_stock_movement');
    }

    /**
     * Категория актива.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class);
    }

    /**
     * Статус актива.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(AssetStatus::class, 'asset_status_id');
    }

    /**
     * Текущий кабинет актива.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Текущий ответственный пользователь.
     */
    public function currentUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_user_id');
    }

    /**
     * История закреплений за пользователями.
     */
    public function userHistory(): HasMany
    {
        return $this->hasMany(AssetUserHistory::class)->latest();
    }
}
