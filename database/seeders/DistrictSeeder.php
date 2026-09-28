<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            'Beji',
            'Pancoran Mas',
            'Sukmajaya',
            'Cimanggis',
            'Cinere',
            'Sawangan',
            'Bojongsari',
            'Tapos',
            'Cipayung',
            'Limo',
            'Cilodong',
        ];

        foreach ($districts as $index => $name) {
            District::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }
    }
}
