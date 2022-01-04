<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class event_signatures extends Model
{
    use HasFactory;
    protected $primaryKey = 'signature_id';
    protected $table = 'event_signatures';

    protected $fillable = [
        'user_id',
        'signature',
    ];
}
