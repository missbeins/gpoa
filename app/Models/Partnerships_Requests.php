<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partnerships_Requests extends Model
{
    use HasFactory;
    protected $table = 'partnership_requests';
    protected $primaryKey = 'request_id';
    protected $fillable = ['event_id','request_by','request_to'];
}
