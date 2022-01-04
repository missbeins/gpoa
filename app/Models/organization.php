<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class organization extends Model
{
    use HasFactory;
    protected $primaryKey='organization_id';
    protected $table='organizations';
    protected $fillable = [
       'organization_name',
       'organization_acronym',
       'organization_type_id',
    ];


}
