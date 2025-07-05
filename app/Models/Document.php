<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'document_type',
        'asset_id',
        'related_request_id',
        'creator_id',
        'file_path',
        'signed_by_user_id',
        'signed_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'signed_at' => 'datetime',
    ];

    /**
     * Получить актив, к которому относится этот документ.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Получить заявку, к которой относится этот документ.
     */
    public function relatedRequest(): BelongsTo
    {
        return $this->belongsTo(Request::class, 'related_request_id');
    }

    /**
     * Получить пользователя, который создал этот документ.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Получить пользователя, который подписал этот документ.
     */
    public function signedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by_user_id');
    }
}
