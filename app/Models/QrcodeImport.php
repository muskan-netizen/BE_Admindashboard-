<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrcodeImport extends Model
{
    use HasFactory;

    public function qrcode(){
        return $this->belongsTo('App\Models\AssignQrcodesToOrder');
     }
 
}
