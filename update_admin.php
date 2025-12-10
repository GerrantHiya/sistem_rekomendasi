<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Update admin password
$affected = DB::table('admin')
    ->where('email', 'gerrante.hiya@gmail.com')
    ->update(['password_hash' => Hash::make('admin123')]);

echo "Updated {$affected} admin(s)\n";

// Verify
$admin = DB::table('admin')->where('email', 'gerrante.hiya@gmail.com')->first();
echo "Email: " . $admin->email . "\n";
echo "Password Hash: " . $admin->password_hash . "\n";
echo "\nLogin with: gerrante.hiya@gmail.com / admin123\n";
