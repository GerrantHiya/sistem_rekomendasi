<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check all admins
$admins = DB::table('admin')->get();
echo "Total admins: " . count($admins) . "\n";

foreach ($admins as $admin) {
    echo "ID: {$admin->ID}, Email: {$admin->email}, Name: {$admin->name}\n";
}

// Create new admin if no admin exists
if (count($admins) == 0) {
    DB::table('admin')->insert([
        'email' => 'admin@mail.com',
        'name' => 'Admin',
        'password_hash' => Hash::make('admin123'),
        'last_login' => now()
    ]);
    echo "Admin created: admin@mail.com / admin123\n";
}
