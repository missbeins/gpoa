<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget_Breakdown extends Model
{
    use HasFactory;
    protected $table = 'budget_breakdowns';
    protected $primaryKey = 'breakdown_id';
    protected $fillable = ['event_id','name','amount'];
}
