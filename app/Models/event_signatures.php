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
        'role_id',
        'organization_id',
        'user_id',
        'signature_path',
    ];

    public function user(){
       return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
