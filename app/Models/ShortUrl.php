<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    use HasFactory;

    public const CODE = 'code';
    public const ORIGINAL_URL = 'original_url';
    public const USER_ID = 'user_id';
    public const COMPANY_ID = 'company_id';

    protected $fillable = [
        self::CODE,
        self::ORIGINAL_URL,
        self::USER_ID,
        self::COMPANY_ID,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
