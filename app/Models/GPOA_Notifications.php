<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GPOA_Notifications extends Model
{
    use HasFactory;
    protected $table='gpoa_notifications';
    protected $primaryKey = 'notification_id';
    protected $fillable = ['message','to','event_id','from','user_id'];
}
