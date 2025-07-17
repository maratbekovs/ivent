<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends Model
{
    use HasFactory;

    /**
     * ИСПРАВЛЕНО: Заменяем 'user_id' на 'requester_id' и добавляем 'subject'.
     */
    protected $fillable = [
        'asset_id',
        'requester_id', // Используем правильное имя столбца
        'subject',
        'description',
        'status',
    ];

    /**
     * Get the asset associated with the request.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * ИСПРАВЛЕНО: Переименовываем связь в 'requester' и указываем правильный foreign key.
     * Get the user who created the request.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }
}
