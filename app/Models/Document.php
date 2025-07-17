<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Document extends Model
{
    use HasFactory;

    /**
     * ИСПРАВЛЕНО: Используем правильные имена полей
     */
    protected $fillable = [
        'title',
        'type',
        'file_path',
        'creator_id', // Правильное имя поля
        'reason',
        'commission_data',
        'asset_id', // Если у вас есть это поле
        'related_request_id',
        'signed_by_user_id',
        'signed_at',
        'notes'
    ];

    protected $casts = [
        'commission_data' => 'array',
        'signed_at' => 'datetime',
    ];

    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'asset_document');
    }

    /**
     * ИСПРАВЛЕНО: Связь с автором документа
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
