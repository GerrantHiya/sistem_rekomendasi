<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Customer;
use App\Models\ProductReview;
use Illuminate\Database\Seeder;

class ProductReviewSeeder extends Seeder
{
    /**
     * Sample review titles and content
     */
    private array $positiveReviews = [
        ['title' => 'Sangat Puas!', 'review' => 'Produk sesuai dengan deskripsi, kualitas sangat bagus. Pengiriman cepat dan packaging aman. Recommended!'],
        ['title' => 'Bagus banget!', 'review' => 'Barang original, kualitas premium. Sangat puas dengan pembelian ini.'],
        ['title' => 'Worth it!', 'review' => 'Harga sebanding dengan kualitas. Bahan nyaman dipakai, desain keren.'],
        ['title' => 'Recommended seller', 'review' => 'Pelayanan ramah, barang sesuai foto. Pasti repeat order!'],
        ['title' => 'Kualitas Top!', 'review' => 'Material berkualitas tinggi, jahitan rapi. Size pas sesuai chart.'],
    ];

    private array $neutralReviews = [
        ['title' => 'Lumayan', 'review' => 'Barang oke, tapi pengiriman agak lama. Overall cukup puas.'],
        ['title' => 'Sesuai harga', 'review' => 'Kualitas standar, sesuai dengan harganya. Tidak mengecewakan.'],
        ['title' => 'Cukup bagus', 'review' => 'Bahan agak tipis tapi masih acceptable. Warna sesuai gambar.'],
    ];

    private array $negativeReviews = [
        ['title' => 'Kurang puas', 'review' => 'Warna agak berbeda dari foto. Kualitas biasa saja untuk harganya.'],
        ['title' => 'Perlu improvement', 'review' => 'Size tidak sesuai chart, terlalu kecil. Tolong perbaiki size chartnya.'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $customers = Customer::all();

        if ($products->isEmpty() || $customers->isEmpty()) {
            $this->command->info('No products or customers found. Skipping review seeder.');
            return;
        }

        $reviewCount = 0;

        foreach ($products as $product) {
            // Random number of reviews per product (0-8)
            $numReviews = rand(0, 8);

            for ($i = 0; $i < $numReviews; $i++) {
                $customer = $customers->random();
                
                // Check if review already exists
                $exists = ProductReview::where('ID_Products', $product->ID_Products)
                    ->where('ID_Customers', $customer->ID_Customers)
                    ->whereNull('ID_Orders')
                    ->exists();

                if ($exists) {
                    continue;
                }

                // Weighted random rating (skewed towards positive)
                $rating = $this->getWeightedRating();
                $reviewData = $this->getReviewForRating($rating);

                ProductReview::create([
                    'ID_Products' => $product->ID_Products,
                    'ID_Customers' => $customer->ID_Customers,
                    'ID_Orders' => null,
                    'rating' => $rating,
                    'title' => $reviewData['title'],
                    'review' => $reviewData['review'],
                    'is_verified_purchase' => rand(0, 1) === 1,
                    'is_approved' => true,
                ]);

                $reviewCount++;
            }
        }

        $this->command->info("Created {$reviewCount} product reviews.");
    }

    /**
     * Get weighted random rating (more likely to be positive)
     */
    private function getWeightedRating(): int
    {
        $weights = [
            5 => 35, // 35% chance
            4 => 30, // 30% chance
            3 => 20, // 20% chance
            2 => 10, // 10% chance
            1 => 5,  // 5% chance
        ];

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($weights as $rating => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $rating;
            }
        }

        return 4;
    }

    /**
     * Get review content based on rating
     */
    private function getReviewForRating(int $rating): array
    {
        if ($rating >= 4) {
            return $this->positiveReviews[array_rand($this->positiveReviews)];
        } elseif ($rating === 3) {
            return $this->neutralReviews[array_rand($this->neutralReviews)];
        } else {
            return $this->negativeReviews[array_rand($this->negativeReviews)];
        }
    }
}
