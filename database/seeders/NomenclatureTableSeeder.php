<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nomenclature;

class NomenclatureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!NomenClature::where(['label'=>'Want To Tip'])->exists()){
            NomenClature::Create(['label'=>'Want To Tip']);
        }
        if(!NomenClature::where(['label'=>'Fixed Fee'])->exists()){
            NomenClature::Create(['label'=>'Fixed Fee']);
        }
        if(!NomenClature::where(['label'=>'P2P'])->exists()){
            NomenClature::Create(['label'=>'P2P']);
        }
    }
}
