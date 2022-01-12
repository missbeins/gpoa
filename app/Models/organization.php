<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrganizationAsset;

class organization extends Model
{
    use HasFactory;
    protected $logo_id = 1;
    protected $primaryKey='organization_id';
    protected $table='organizations';
    protected $fillable = [
       'organization_name',
       'organization_acronym',
       'organization_type_id',
    ];
    public function logo()
    {
        return $this->hasOne(OrganizationAsset::class, 'organization_id')->where('asset_type_id', $this->logo_id)->limit(1);
    }


}
