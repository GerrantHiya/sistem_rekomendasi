<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class FitAndGoProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, add more brands suitable for Fit & Go
        $brands = $this->createBrands();
        
        // Create subcategories for Bottom category
        $this->createSubcategories();
        
        // Then create products per category
        $this->createAccessoriesProducts($brands);
        $this->createTopProducts($brands);
        $this->createBottomProducts($brands);
        $this->createShoesProducts($brands);
        
        $this->command->info('Fit & Go products seeded successfully!');
    }

    private function createSubcategories(): void
    {
        // Add subcategories for Bottom (Category ID: 3)
        $bottomSubcategories = [
            ['name' => 'Shorts', 'ID_Categories' => 3],
            ['name' => 'Pants', 'ID_Categories' => 3],
            ['name' => 'Leggings', 'ID_Categories' => 3],
        ];

        foreach ($bottomSubcategories as $sub) {
            Subcategory::firstOrCreate(
                ['name' => $sub['name'], 'ID_Categories' => $sub['ID_Categories']]
            );
        }
    }

    private function createBrands(): array
    {
        $brandNames = [
            'Nike',
            'Adidas',
            'Puma',
            'Under Armour',
            'New Balance',
            'Reebok',
            'Asics',
            'Fila',
            'Converse',
            'Vans',
            'Skechers',
            'Levi\'s',
            'Uniqlo',
            'H&M Sport',
            'Casio',
            'Garmin',
            'Fitbit',
        ];

        $brands = [];
        foreach ($brandNames as $name) {
            $brand = Brand::firstOrCreate(['name' => $name]);
            $brands[$name] = $brand->ID_Brand;
        }

        return $brands;
    }

    /**
     * Create Accessories products (Category ID: 1)
     * Subcategories: 1=Bracelets, 2=Watches, 5=Necklace
     */
    private function createAccessoriesProducts(array $brands): void
    {
        $products = [
            // Watches (Subcategory 2)
            [
                'name' => 'Garmin Forerunner 255',
                'sku' => 'GAR-FR255',
                'brand' => 'Garmin',
                'subcategory' => 2,
                'gender' => 3, // Unisex
                'description' => 'GPS running smartwatch dengan fitur training metrics, heart rate monitor, dan battery life hingga 14 hari. Cocok untuk pelari serius.',
                'variants' => [
                    ['color' => 'Black', 'price' => 5499000, 'stock' => 15],
                    ['color' => 'Whitestone', 'price' => 5499000, 'stock' => 12],
                ]
            ],
            [
                'name' => 'Fitbit Charge 6',
                'sku' => 'FIT-CHG6',
                'brand' => 'Fitbit',
                'subcategory' => 2,
                'gender' => 3,
                'description' => 'Advanced fitness tracker dengan built-in GPS, heart rate zones, dan stress management tools. 7 hari battery life.',
                'variants' => [
                    ['color' => 'Obsidian Black', 'price' => 2399000, 'stock' => 25],
                    ['color' => 'Coral Rose', 'price' => 2399000, 'stock' => 20],
                ]
            ],
            [
                'name' => 'Casio G-Shock GBD-200',
                'sku' => 'CAS-GBD200',
                'brand' => 'Casio',
                'subcategory' => 2,
                'gender' => 1, // Male
                'description' => 'Jam tangan digital tahan banting dengan Bluetooth connectivity, step counter, dan training functions. Water resistant 200M.',
                'variants' => [
                    ['color' => 'Black', 'price' => 2850000, 'stock' => 18],
                ]
            ],
            [
                'name' => 'Garmin Venu 3',
                'sku' => 'GAR-VN3',
                'brand' => 'Garmin',
                'subcategory' => 2,
                'gender' => 3,
                'description' => 'Premium GPS smartwatch dengan AMOLED display, advanced sleep tracking, dan fitness coaching. Stylish untuk everyday wear.',
                'variants' => [
                    ['color' => 'Soft Gold', 'price' => 7299000, 'stock' => 10],
                    ['color' => 'Slate Gray', 'price' => 7299000, 'stock' => 8],
                ]
            ],
            // Sport Bracelets (Subcategory 1)
            [
                'name' => 'Nike Sport Loop Band',
                'sku' => 'NIK-SLB',
                'brand' => 'Nike',
                'subcategory' => 1,
                'gender' => 3,
                'description' => 'Breathable woven nylon strap yang ringan dan nyaman untuk olahraga. Hook-and-loop fastener untuk fit sempurna.',
                'variants' => [
                    ['color' => 'Summit White', 'price' => 649000, 'stock' => 40],
                    ['color' => 'Game Royal Blue', 'price' => 649000, 'stock' => 35],
                ]
            ],
            [
                'name' => 'Under Armour Wristband',
                'sku' => 'UA-WB',
                'brand' => 'Under Armour',
                'subcategory' => 1,
                'gender' => 3,
                'description' => 'Performance sweatband yang menyerap keringat dengan cepat. Material anti-odor dan quick-dry.',
                'variants' => [
                    ['color' => 'Black/Red', 'price' => 199000, 'stock' => 60],
                    ['color' => 'Navy/White', 'price' => 199000, 'stock' => 50],
                ]
            ],
            [
                'name' => 'Adidas Interval Reversible Band',
                'sku' => 'ADI-IRB',
                'brand' => 'Adidas',
                'subcategory' => 1,
                'gender' => 3,
                'description' => 'Double-sided headband yang bisa dibalik untuk 2 look berbeda. Moisture-wicking fabric.',
                'variants' => [
                    ['color' => 'Black/White', 'price' => 249000, 'stock' => 45],
                ]
            ],
            // Necklace/Pendants (Subcategory 5)
            [
                'name' => 'Titanium Sport Dog Tag',
                'sku' => 'TIT-SDT',
                'brand' => 'Nike',
                'subcategory' => 5,
                'gender' => 1,
                'description' => 'Kalung titanium dengan pendant dog tag style. Hypoallergenic dan tahan karat, cocok untuk aktivitas outdoor.',
                'variants' => [
                    ['color' => 'Silver', 'price' => 450000, 'stock' => 25],
                    ['color' => 'Matte Black', 'price' => 450000, 'stock' => 20],
                ]
            ],
        ];

        $this->insertProducts($products, 1);
    }

    /**
     * Create Top products (Category ID: 2)
     * Subcategories: 3=T-Shirts, 4=Shirt
     */
    private function createTopProducts(array $brands): void
    {
        $products = [
            // T-Shirts (Subcategory 3)
            [
                'name' => 'Nike Dri-FIT Running Tee',
                'sku' => 'NIK-DRFT',
                'brand' => 'Nike',
                'subcategory' => 3,
                'gender' => 1,
                'description' => 'Kaos lari dengan teknologi Dri-FIT yang menyerap keringat dan cepat kering. Lightweight dan breathable untuk performa maksimal.',
                'variants' => [
                    ['color' => 'Black', 'price' => 549000, 'stock' => 50],
                    ['color' => 'Volt Green', 'price' => 549000, 'stock' => 40],
                ]
            ],
            [
                'name' => 'Adidas Aeroready Training Tee',
                'sku' => 'ADI-ARTT',
                'brand' => 'Adidas',
                'subcategory' => 3,
                'gender' => 1,
                'description' => 'Kaos training dengan teknologi AEROREADY moisture-absorbing. Regular fit dengan recycled materials.',
                'variants' => [
                    ['color' => 'White', 'price' => 499000, 'stock' => 45],
                    ['color' => 'Legend Ink Blue', 'price' => 499000, 'stock' => 38],
                ]
            ],
            [
                'name' => 'Under Armour Tech 2.0 Tee',
                'sku' => 'UA-T2T',
                'brand' => 'Under Armour',
                'subcategory' => 3,
                'gender' => 1,
                'description' => 'Ultra-soft UA Tech fabric yang cepat kering dan sangat breathable. Anti-odor technology mencegah bau.',
                'variants' => [
                    ['color' => 'Academy Blue', 'price' => 479000, 'stock' => 55],
                    ['color' => 'Graphite', 'price' => 479000, 'stock' => 48],
                ]
            ],
            [
                'name' => 'Puma Essential Logo Tee',
                'sku' => 'PUM-ELT',
                'brand' => 'Puma',
                'subcategory' => 3,
                'gender' => 3,
                'description' => 'Classic cotton tee dengan PUMA Cat Logo. Regular fit, casual style untuk everyday wear.',
                'variants' => [
                    ['color' => 'Peacoat Navy', 'price' => 399000, 'stock' => 60],
                    ['color' => 'Medium Gray', 'price' => 399000, 'stock' => 55],
                ]
            ],
            [
                'name' => 'Nike Pro Compression Top',
                'sku' => 'NIK-PCT',
                'brand' => 'Nike',
                'subcategory' => 3,
                'gender' => 1,
                'description' => 'Baselayer compression tee yang memberikan support otot. Dri-FIT technology untuk kenyamanan maksimal.',
                'variants' => [
                    ['color' => 'Black', 'price' => 649000, 'stock' => 30],
                ]
            ],
            [
                'name' => 'Reebok CrossFit Tee',
                'sku' => 'REB-CFT',
                'brand' => 'Reebok',
                'subcategory' => 3,
                'gender' => 1,
                'description' => 'Durability meets comfort. Designed untuk high-intensity training dengan reinforced seams.',
                'variants' => [
                    ['color' => 'Pure Grey', 'price' => 459000, 'stock' => 42],
                    ['color' => 'Vector Red', 'price' => 459000, 'stock' => 35],
                ]
            ],
            // Women's Tops
            [
                'name' => 'Nike Swoosh Sports Bra',
                'sku' => 'NIK-SSB',
                'brand' => 'Nike',
                'subcategory' => 3,
                'gender' => 2, // Female
                'description' => 'Medium-support sports bra dengan Dri-FIT technology. Removable pads dan racerback design.',
                'variants' => [
                    ['color' => 'Black', 'price' => 599000, 'stock' => 40],
                    ['color' => 'Sunset Pulse', 'price' => 599000, 'stock' => 35],
                ]
            ],
            [
                'name' => 'Adidas Designed 2 Move Tank',
                'sku' => 'ADI-D2MT',
                'brand' => 'Adidas',
                'subcategory' => 3,
                'gender' => 2,
                'description' => 'Lightweight training tank dengan AEROREADY. Relaxed fit dengan dropped armholes.',
                'variants' => [
                    ['color' => 'White', 'price' => 429000, 'stock' => 45],
                    ['color' => 'Bliss Lilac', 'price' => 429000, 'stock' => 38],
                ]
            ],
        ];

        $this->insertProducts($products, 2);
    }

    /**
     * Create Bottom products (Category ID: 3)
     */
    private function createBottomProducts(array $brands): void
    {
        $products = [
            [
                'name' => 'Nike Dri-FIT Challenger Shorts',
                'sku' => 'NIK-DCS',
                'brand' => 'Nike',
                'subcategory' => 8, // Shorts
                'gender' => 1,
                'description' => 'Running shorts dengan built-in brief liner. Dri-FIT fabric dan side mesh panels untuk ventilasi optimal.',
                'variants' => [
                    ['color' => 'Black', 'price' => 599000, 'stock' => 45],
                    ['color' => 'Midnight Navy', 'price' => 599000, 'stock' => 40],
                ]
            ],
            [
                'name' => 'Adidas Tiro 23 Training Pants',
                'sku' => 'ADI-T23P',
                'brand' => 'Adidas',
                'subcategory' => 9, // Pants
                'gender' => 1,
                'description' => 'Classic training pants dengan AEROREADY. Tapered leg, zip pockets, dan 3-stripes iconic design.',
                'variants' => [
                    ['color' => 'Black/White', 'price' => 899000, 'stock' => 35],
                    ['color' => 'Team Navy', 'price' => 899000, 'stock' => 30],
                ]
            ],
            [
                'name' => 'Under Armour Speedpocket Shorts',
                'sku' => 'UA-SPS',
                'brand' => 'Under Armour',
                'subcategory' => 8, // Shorts
                'gender' => 1,
                'description' => 'Running shorts dengan SpeedPocket™ waistband untuk phone storage. Ultra-lightweight dan quick-dry.',
                'variants' => [
                    ['color' => 'Pitch Gray', 'price' => 749000, 'stock' => 38],
                ]
            ],
            [
                'name' => 'Puma Essentials Sweatpants',
                'sku' => 'PUM-ESP',
                'brand' => 'Puma',
                'subcategory' => 9, // Pants
                'gender' => 3,
                'description' => 'Comfortable cotton-blend sweatpants untuk lounging atau casual training. Elastic waist dan cuffs.',
                'variants' => [
                    ['color' => 'Medium Gray', 'price' => 699000, 'stock' => 50],
                    ['color' => 'Peacoat Navy', 'price' => 699000, 'stock' => 45],
                ]
            ],
            [
                'name' => 'Nike Pro Leggings',
                'sku' => 'NIK-PL',
                'brand' => 'Nike',
                'subcategory' => 10, // Leggings
                'gender' => 2,
                'description' => 'High-waisted training leggings dengan Dri-FIT. Compression fit untuk support maksimal.',
                'variants' => [
                    ['color' => 'Black', 'price' => 799000, 'stock' => 40],
                    ['color' => 'Smoke Grey', 'price' => 799000, 'stock' => 35],
                ]
            ],
            [
                'name' => 'Adidas Yoga 7/8 Tights',
                'sku' => 'ADI-Y78T',
                'brand' => 'Adidas',
                'subcategory' => 10, // Leggings
                'gender' => 2,
                'description' => 'Buttery-soft yoga tights dengan high rise. 4-way stretch untuk movement bebas.',
                'variants' => [
                    ['color' => 'Carbon Black', 'price' => 849000, 'stock' => 32],
                    ['color' => 'Wonder Mauve', 'price' => 849000, 'stock' => 28],
                ]
            ],
            [
                'name' => 'Reebok Training Essentials Shorts',
                'sku' => 'REB-TES',
                'brand' => 'Reebok',
                'subcategory' => 8, // Shorts
                'gender' => 1,
                'description' => 'Versatile training shorts dengan Speedwick fabric. Regular fit dengan elastic waistband.',
                'variants' => [
                    ['color' => 'Black', 'price' => 499000, 'stock' => 48],
                    ['color' => 'Vector Navy', 'price' => 499000, 'stock' => 42],
                ]
            ],
            [
                'name' => 'Fila Sport Leggings',
                'sku' => 'FIL-SL',
                'brand' => 'Fila',
                'subcategory' => 10, // Leggings
                'gender' => 2,
                'description' => 'Classic Fila leggings dengan logo tape di sisi. Comfort waistband dan moisture-wicking.',
                'variants' => [
                    ['color' => 'Black', 'price' => 549000, 'stock' => 38],
                ]
            ],
        ];

        $this->insertProducts($products, 3);
    }

    /**
     * Create Shoes products (Category ID: 4)
     * Subcategories: 6=Casual, 7=Running
     */
    private function createShoesProducts(array $brands): void
    {
        $products = [
            // Running Shoes (Subcategory 7)
            [
                'name' => 'Nike Air Zoom Pegasus 40',
                'sku' => 'NIK-AZP40',
                'brand' => 'Nike',
                'subcategory' => 7,
                'gender' => 3,
                'description' => 'Sepatu lari legendary dengan React foam dan Zoom Air unit. Cocok untuk daily training hingga marathon prep.',
                'variants' => [
                    ['color' => 'Black/White', 'price' => 1899000, 'stock' => 25],
                    ['color' => 'Total Orange', 'price' => 1899000, 'stock' => 20],
                ]
            ],
            [
                'name' => 'Adidas Ultraboost Light',
                'sku' => 'ADI-UBL',
                'brand' => 'Adidas',
                'subcategory' => 7,
                'gender' => 3,
                'description' => 'Lightest Ultraboost ever dengan Light BOOST midsole. Energy return maksimal untuk long runs.',
                'variants' => [
                    ['color' => 'Core Black', 'price' => 2899000, 'stock' => 18],
                    ['color' => 'Cloud White', 'price' => 2899000, 'stock' => 15],
                ]
            ],
            [
                'name' => 'Asics Gel-Nimbus 25',
                'sku' => 'ASI-GN25',
                'brand' => 'Asics',
                'subcategory' => 7,
                'gender' => 3,
                'description' => 'Maximum cushioning running shoe dengan FF BLAST PLUS Eco cushioning. Plush comfort untuk daily miles.',
                'variants' => [
                    ['color' => 'Sheet Rock/Blue', 'price' => 2499000, 'stock' => 20],
                    ['color' => 'Black/Pure Silver', 'price' => 2499000, 'stock' => 18],
                ]
            ],
            [
                'name' => 'New Balance Fresh Foam 1080v13',
                'sku' => 'NB-FF1080',
                'brand' => 'New Balance',
                'subcategory' => 7,
                'gender' => 3,
                'description' => 'Premium cushioned running shoe dengan Fresh Foam X midsole. Hypoknit upper untuk fit yang sempurna.',
                'variants' => [
                    ['color' => 'Eclipse Navy', 'price' => 2299000, 'stock' => 22],
                    ['color' => 'Neon Dragonfly', 'price' => 2299000, 'stock' => 16],
                ]
            ],
            [
                'name' => 'Skechers GOrun Swirl Tech',
                'sku' => 'SKE-GST',
                'brand' => 'Skechers',
                'subcategory' => 7,
                'gender' => 3,
                'description' => 'Lightweight performance runner dengan Hyper Burst midsole. Seamless knit upper dan breathable design.',
                'variants' => [
                    ['color' => 'Black/White', 'price' => 1599000, 'stock' => 28],
                ]
            ],
            // Casual Shoes (Subcategory 6)
            [
                'name' => 'Converse Chuck Taylor All Star',
                'sku' => 'CON-CTAS',
                'brand' => 'Converse',
                'subcategory' => 6,
                'gender' => 3,
                'description' => 'Iconic canvas sneaker yang timeless. OrthoLite insole untuk comfort lebih baik.',
                'variants' => [
                    ['color' => 'Optical White', 'price' => 899000, 'stock' => 35],
                    ['color' => 'Black', 'price' => 899000, 'stock' => 40],
                ]
            ],
            [
                'name' => 'Vans Old Skool',
                'sku' => 'VAN-OS',
                'brand' => 'Vans',
                'subcategory' => 6,
                'gender' => 3,
                'description' => 'Classic skate shoe dengan signature side stripe. Durable suede dan canvas upper.',
                'variants' => [
                    ['color' => 'Black/White', 'price' => 999000, 'stock' => 30],
                    ['color' => 'Navy/White', 'price' => 999000, 'stock' => 25],
                ]
            ],
            [
                'name' => 'Nike Air Force 1 07',
                'sku' => 'NIK-AF1',
                'brand' => 'Nike',
                'subcategory' => 6,
                'gender' => 3,
                'description' => 'Legendary basketball shoe yang jadi icon streetwear. Premium leather upper dengan Air cushioning.',
                'variants' => [
                    ['color' => 'White/White', 'price' => 1549000, 'stock' => 28],
                    ['color' => 'Black/Black', 'price' => 1549000, 'stock' => 25],
                ]
            ],
            [
                'name' => 'Adidas Stan Smith',
                'sku' => 'ADI-SS',
                'brand' => 'Adidas',
                'subcategory' => 6,
                'gender' => 3,
                'description' => 'Timeless tennis-inspired sneaker dengan clean leather upper. Perforated 3-Stripes dan iconic heel tab.',
                'variants' => [
                    ['color' => 'White/Green', 'price' => 1599000, 'stock' => 22],
                    ['color' => 'White/Navy', 'price' => 1599000, 'stock' => 20],
                ]
            ],
        ];

        $this->insertProducts($products, 4);
    }

    /**
     * Insert products into database
     */
    private function insertProducts(array $products, int $categoryId): void
    {
        foreach ($products as $productData) {
            $brand = Brand::where('name', $productData['brand'])->first();
            
            if (!$brand) {
                $this->command->warn("Brand {$productData['brand']} not found, skipping {$productData['name']}");
                continue;
            }

            // Check if product already exists
            $existingProduct = Product::where('SKU', $productData['sku'])->first();
            if ($existingProduct) {
                continue;
            }

            $product = Product::create([
                'Name' => $productData['name'],
                'SKU' => $productData['sku'],
                'ID_Brand' => $brand->ID_Brand,
                'ID_Gender' => $productData['gender'],
                'ID_Categories' => $categoryId,
                'ID_SubCategories' => $productData['subcategory'],
                'Description' => $productData['description'],
            ]);

            // Create variants
            $variantIndex = 1;
            foreach ($productData['variants'] as $variant) {
                ProductVariant::create([
                    'variant_sku' => $productData['sku'] . '-V' . $variantIndex,
                    'ID_Product' => $product->ID_Products,
                    'ID_Size' => 1, // Default size
                    'color' => $variant['color'],
                    'price' => $variant['price'],
                    'stock_qty' => $variant['stock'],
                    'weight_gram' => rand(200, 800),
                ]);
                $variantIndex++;
            }

            $this->command->line("  ✓ Created: {$productData['name']}");
        }
    }
}
