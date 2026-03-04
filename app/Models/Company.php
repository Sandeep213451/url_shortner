<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public const NAME = 'name';

    protected $fillable = [self::NAME];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function shortUrls()
    {
        return $this->hasMany(ShortUrl::class);
    }
}
