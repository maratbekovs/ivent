<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'room_id',
        'current_user_id',
        'serial_number',
        'inventory_number',
        'mac_address',
        'purchase_year',
        'warranty_expires_at',
        'asset_status_id',
        'qr_code_data', // ИЗМЕНЕНО
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'warranty_expires_at' => 'date',
    ];

    /**
     * Получить категорию, к которой принадлежит актив.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'category_id');
    }

    /**
     * Получить кабинет, в котором находится актив.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Получить текущего ответственного за актив.
     */
    public function currentUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_user_id');
    }

    /**
     * Получить статус актива.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(AssetStatus::class, 'asset_status_id');
    }

    /**
     * Получить историю закреплений актива.
     */
    public function userHistory(): HasMany
    {
        return $this->hasMany(AssetUserHistory::class);
    }

    /**
     * Получить все перемещения, связанные с этим активом.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Получить все заявки, связанные с этим активом.
     */
    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    /**
     * Получить все документы, связанные с этим активом.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
