<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokja extends Model
{
    use HasFactory;

    protected $table = 'pokjas';

    protected $fillable = [
        'name',
        'slug',
    ];

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class, 'pokja_id');
    }

    public function arsips()
    {
        return $this->hasMany(Arsip::class, 'pokja_id');
    }
}