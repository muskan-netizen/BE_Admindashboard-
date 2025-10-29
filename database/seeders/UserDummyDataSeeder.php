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
            ['name' => 'Yash Chauhan', 'image' => 'prods/nhehtcZPaid6d2JuP3JWv8c6DMWX1KQMKxCo2veS.png'],
            ['name' => 'Anshul Garg', 'image' => 'prods/SsdhTAt99tYTsCR1xdGheKZUAbhElw8KnUJEA463.png'],
            ['name' => 'Harsh Sharma', 'image' => 'prods/tANJABWO8G6f9uljvYTKc3KxT9F1QTcx5yVZD5th.png'],
            ['name' => 'Priya Verma', 'image' => 'prods/6yX3IjkJAXFUdUDYSFcJ0xKw5XqkNzKNQtGmheKS.jpg'],
            ['name' => 'Rohan Mehta', 'image' => 'prods/nhehtcZPaid6d2JuP3JWv8c6DMWX1KQMKxCo2veS.png'],
            ['name' => 'Neha Kapoor', 'image' => 'prods/6yX3IjkJAXFUdUDYSFcJ0xKw5XqkNzKNQtGmheKS.jpg'],
            ['name' => 'Aarav Singh', 'image' => 'prods/nhehtcZPaid6d2JuP3JWv8c6DMWX1KQMKxCo2veS.png'],
            ['name' => 'Kavya Joshi', 'image' => 'prods/6yX3IjkJAXFUdUDYSFcJ0xKw5XqkNzKNQtGmheKS.jpg'],
            ['name' => 'Mohit Bansal', 'image' => 'prods/nhehtcZPaid6d2JuP3JWv8c6DMWX1KQMKxCo2veS.png'],
            ['name' => 'Isha Malhotra', 'image' => 'prods/6yX3IjkJAXFUdUDYSFcJ0xKw5XqkNzKNQtGmheKS.jpg'],
        ];

        foreach ($items as $item) {
            UserDummyData::updateOrCreate(
                ['name' => $item['name']],
                ['image' => $item['image']]
            );
        }
    }
}
