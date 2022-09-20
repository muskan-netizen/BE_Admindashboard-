<?php
namespace App\Http\Traits;

use Illuminate\Support\Facades\Schema;


trait ValidatorTrait{

    /** check if column exits in table
     * @param string $tableName
     * @param string @columnName
     * @return boolean true or false
     * @author sudhanshu sharma
     */
    public function checkColumnExists($tableName, $columnName){
        if (Schema::hasColumn($tableName, $columnName)){
            return true;
        }else{
            return false;
        }
    }

}