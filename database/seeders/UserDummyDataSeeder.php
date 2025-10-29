<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserDummyData;

class UserDummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                ['name' => 'RedFox', 'image' => 'prods/nhehtcZPaid6d2JuP3JWv8c6DMWX1KQMKxCo2veS.png'],
                ['name' => 'BlueTiger', 'image' => 'prods/SsdhTAt99tYTsCR1xdGheKZUAbhElw8KnUJEA463.png'],
                ['name' => 'SilverWolf', 'image' => 'prods/tANJABWO8G6f9uljvYTKc3KxT9F1QTcx5yVZD5th.png'],
                ['name' => 'GoldenEagle', 'image' => 'prods/6yX3IjkJAXFUdUDYSFcJ0xKw5XqkNzKNQtGmheKS.jpg'],
                ['name' => 'ShadowBear', 'image' => 'prods/nhehtcZPaid6d2JuP3JWv8c6DMWX1KQMKxCo2veS.png'],
                ['name' => 'CrystalFox', 'image' => 'prods/6yX3IjkJAXFUdUDYSFcJ0xKw5XqkNzKNQtGmheKS.jpg'],
                ['name' => 'ThunderLion', 'image' => 'prods/nhehtcZPaid6d2JuP3JWv8c6DMWX1KQMKxCo2veS.png'],
                ['name' => 'AuroraWolf', 'image' => 'prods/6yX3IjkJAXFUdUDYSFcJ0xKw5XqkNzKNQtGmheKS.jpg'],
                ['name' => 'FrostDragon', 'image' => 'prods/nhehtcZPaid6d2JuP3JWv8c6DMWX1KQMKxCo2veS.png'],
                ['name' => 'StarPhoenix', 'image' => 'prods/6yX3IjkJAXFUdUDYSFcJ0xKw5XqkNzKNQtGmheKS.jpg'],
            ],
        ];

        foreach ($items as $item) {
            UserDummyData::updateOrCreate(
                ['name' => $item['name']],
                ['image' => $item['image']]
            );
        }
    }
}
