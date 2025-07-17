<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'movement_date',
        'from_location_id',
        'to_location_id',
        'notes',
    ];

    protected $casts = [
        'movement_date' => 'datetime',
    ];

    /**
     * Активы, связанные с этим перемещением.
     */
    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'asset_stock_movement');
    }

    /**
     * Пользователь, который совершил перемещение.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Локация, из которой было совершено перемещение.
     */
    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'from_location_id');
    }

    /**
     * Локация, в которую было совершено перемещение.
     */
    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'to_location_id');
    }
}