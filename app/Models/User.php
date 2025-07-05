<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles; // Добавлено

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // Добавлено HasRoles

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'position',
        // 'role_id', // Убедись, что этой строки здесь НЕТ
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Метод role() должен быть удален, так как мы используем HasRoles от Spatie

    /**
     * Получить активы, за которые пользователь в данный момент ответственен.
     */
    public function currentAssets(): HasMany
    {
        return $this->hasMany(Asset::class, 'current_user_id');
    }

    /**
     * Получить историю закреплений активов за этим пользователем.
     */
    public function assetUserHistory(): HasMany
    {
        return $this->hasMany(AssetUserHistory::class);
    }

    /**
     * Получить все перемещения на складе, совершенные этим пользователем.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Получить заявки, созданные этим пользователем.
     */
    public function createdRequests(): HasMany
    {
        return $this->hasMany(Request::class, 'requester_id');
    }

    /**
     * Получить заявки, назначенные этому пользователю.
     */
    public function assignedRequests(): HasMany
    {
        return $this->hasMany(Request::class, 'assigned_to_id');
    }

    /**
     * Получить документы, созданные этим пользователем.
     */
    public function createdDocuments(): HasMany
    {
        return $this->hasMany(Document::class, 'creator_id');
    }

    /**
     * Получить документы, подписанные этим пользователем.
     */
    public function signedDocuments(): HasMany
    {
        return $this->hasMany(Document::class, 'signed_by_user_id');
    }
}
