<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Size;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Standard clothing sizes with measurements in cm
     * Applicable for Top (T-shirts, Shirts) and Bottom (Pants, Shorts, Leggings)
     */
    public function run(): void
    {
        $sizes = [
            // Special sizes
            [
                'name' => 'N/A',
                'chest' => null,
                'body_length' => null,
                'waist' => null,
                'hip' => null,
                'thigh' => null,
            ],
            // Standard clothing sizes
            [
                'name' => 'XS',
                'chest' => 86,
                'body_length' => 66,
                'waist' => 71,
                'hip' => 89,
                'thigh' => 53,
            ],
            [
                'name' => 'S',
                'chest' => 91,
                'body_length' => 69,
                'waist' => 76,
                'hip' => 94,
                'thigh' => 55,
            ],
            [
                'name' => 'M',
                'chest' => 97,
                'body_length' => 72,
                'waist' => 81,
                'hip' => 99,
                'thigh' => 58,
            ],
            [
                'name' => 'L',
                'chest' => 102,
                'body_length' => 74,
                'waist' => 86,
                'hip' => 104,
                'thigh' => 61,
            ],
            [
                'name' => 'XL',
                'chest' => 107,
                'body_length' => 76,
                'waist' => 91,
                'hip' => 109,
                'thigh' => 64,
            ],
            [
                'name' => 'XXL',
                'chest' => 114,
                'body_length' => 78,
                'waist' => 99,
                'hip' => 117,
                'thigh' => 68,
            ],
            [
                'name' => 'XXXL',
                'chest' => 122,
                'body_length' => 80,
                'waist' => 107,
                'hip' => 124,
                'thigh' => 72,
            ],
        ];

        foreach ($sizes as $size) {
            Size::firstOrCreate(
                ['name' => $size['name']],
                $size
            );
        }

        $this->command->info('✅ Size data seeded successfully!');
        $this->command->table(
            ['ID', 'Name', 'Chest', 'Body Length', 'Waist', 'Hip', 'Thigh'],
            Size::all()->map(fn($s) => [
                $s->ID_Size,
                $s->name,
                $s->chest ? "{$s->chest}cm" : '-',
                $s->body_length ? "{$s->body_length}cm" : '-',
                $s->waist ? "{$s->waist}cm" : '-',
                $s->hip ? "{$s->hip}cm" : '-',
                $s->thigh ? "{$s->thigh}cm" : '-',
            ])->toArray()
        );
    }
}
