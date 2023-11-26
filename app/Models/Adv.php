<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adv extends Model
{
    use HasFactory;

    protected $fillable = [
        "adv_title",
        "adv_file",
        "adv_link",
        "adv_duration_seconds",
        "adv_loop_seconds",
    ];
}
