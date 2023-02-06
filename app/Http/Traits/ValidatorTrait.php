<?php
namespace App\Http\Traits;

use Illuminate\Support\Facades\Schema;


trait ValidatorTrait{
    /**
     * checkColumnExists
     *
     * @param  mixed $tableName
     * @param  mixed $columnName
     * @return void
     */
    public function checkColumnExists($tableName, $columnName){
        if (Schema::hasColumn($tableName, $columnName)){
            return true;
        }else{
            return false;
        }
    }

}