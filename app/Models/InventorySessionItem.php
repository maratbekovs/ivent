<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventorySessionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_session_id',
        'asset_id',
        'expected_room_id',
        'found_in_room_id',
        'status',
    ];

    /**
     * Get the session this item belongs to.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(InventorySession::class, 'inventory_session_id');
    }

    /**
     * Get the asset associated with this item.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Get the room where the asset was expected to be.
     */
    public function expectedRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'expected_room_id');
    }

    /**
     * Get the room where the asset was actually found.
     */
    public function foundInRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'found_in_room_id');
    }
}
