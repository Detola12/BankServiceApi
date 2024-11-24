<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_no',
        'account_type',
        'pin'
    ];

    protected function casts()
    {
        return [
            'pin' => 'hashed'
        ];
    }

    protected $hidden = [
        'updated_at',
        'balance',
        'pin'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
