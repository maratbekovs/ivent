<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $table = 'stock_movements'; // Указываем имя таблицы явно

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'asset_id',
        'type',
        'from_location_id',
        'to_location_id',
        'user_id',
        'movement_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'movement_date' => 'datetime',
    ];

    /**
     * Получить актив, связанный с этим перемещением.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Получить локацию (кабинет), откуда был перемещен актив.
     */
    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'from_location_id');
    }

    /**
     * Получить локацию (кабинет), куда был перемещен актив.
     */
    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'to_location_id');
    }

    /**
     * Получить пользователя, который совершил это перемещение.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
