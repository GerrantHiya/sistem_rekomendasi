<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Customer;
use App\Services\HybridRecommendationService;
use Illuminate\Console\Command;

class TestRecommendations extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'recommendations:test 
                            {--customer= : Customer ID for personalized recommendations}
                            {--product= : Product ID for similar products}
                            {--trending : Show trending products}
                            {--top-rated : Show top rated products}
                            {--search= : Search query}
                            {--limit=8 : Number of results}
                            {--explain : Show detailed score breakdown}';

    /**
     * The console command description.
     */
    protected $description = 'Test the hybrid recommendation system';

    /**
     * Execute the console command.
     */
    public function handle(HybridRecommendationService $recommendationService): int
    {
        $limit = (int) $this->option('limit');

        if ($this->option('customer')) {
            return $this->showPersonalizedRecommendations($recommendationService, (int) $this->option('customer'), $limit);
        }

        if ($this->option('product')) {
            return $this->showSimilarProducts($recommendationService, (int) $this->option('product'), $limit);
        }

        if ($this->option('trending')) {
            return $this->showTrendingProducts($recommendationService, $limit);
        }

        if ($this->option('top-rated')) {
            return $this->showTopRatedProducts($recommendationService, $limit);
        }

        if ($this->option('search')) {
            return $this->showSearchResults($recommendationService, $this->option('search'), $limit);
        }

        // Default: show menu
        $this->showMenu($recommendationService, $limit);
        return Command::SUCCESS;
    }

    private function showPersonalizedRecommendations(HybridRecommendationService $service, int $customerId, int $limit): int
    {
        $customer = Customer::find($customerId);
        
        if (!$customer) {
            $this->error("Customer with ID {$customerId} not found.");
            return Command::FAILURE;
        }

        $this->info("🎯 Personalized Recommendations for: {$customer->name}");
        $this->newLine();

        $recommendations = $service->getPersonalizedRecommendations($customerId, $limit);

        if ($recommendations->isEmpty()) {
            $this->warn("No recommendations found.");
            return Command::SUCCESS;
        }

        $tableData = [];
        foreach ($recommendations as $product) {
            $row = [
                'ID' => $product->ID_Products,
                'Name' => mb_substr($product->Name, 0, 35),
                'Brand' => $product->brand->name ?? '-',
                'Category' => $product->category->name ?? '-',
                'Score' => $product->recommendation_score . '%',
                'Rating' => $product->average_rating . ' ⭐',
                'Reviews' => $product->review_count,
            ];

            if ($this->option('explain') && isset($product->score_breakdown)) {
                $row['TF-IDF'] = round($product->score_breakdown['content_similarity'] * 100, 1) . '%';
                $row['RatingS'] = round($product->score_breakdown['rating_score'] * 100, 1) . '%';
                $row['Popular'] = round($product->score_breakdown['popularity_score'] * 100, 1) . '%';
                $row['CatAff'] = round($product->score_breakdown['category_affinity'] * 100, 1) . '%';
            }

            $tableData[] = $row;
        }

        $headers = array_keys($tableData[0]);
        $this->table($headers, $tableData);

        return Command::SUCCESS;
    }

    private function showSimilarProducts(HybridRecommendationService $service, int $productId, int $limit): int
    {
        $product = Product::with(['brand', 'category'])->find($productId);
        
        if (!$product) {
            $this->error("Product with ID {$productId} not found.");
            return Command::FAILURE;
        }

        $this->info("🔗 Products Similar to: {$product->Name}");
        $this->line("   Brand: " . ($product->brand->name ?? '-') . " | Category: " . ($product->category->name ?? '-'));
        $this->newLine();

        $recommendations = $service->getSimilarProducts($product, $limit);

        if ($recommendations->isEmpty()) {
            $this->warn("No similar products found.");
            return Command::SUCCESS;
        }

        $tableData = [];
        foreach ($recommendations as $p) {
            $tableData[] = [
                'ID' => $p->ID_Products,
                'Name' => mb_substr($p->Name, 0, 40),
                'Brand' => $p->brand->name ?? '-',
                'Category' => $p->category->name ?? '-',
                'Similarity' => $p->similarity_score . '%',
                'Content Match' => $p->content_match . '%',
                'Rating' => $p->average_rating . ' ⭐',
            ];
        }

        $this->table(array_keys($tableData[0]), $tableData);

        return Command::SUCCESS;
    }

    private function showTrendingProducts(HybridRecommendationService $service, int $limit): int
    {
        $this->info("📈 Trending Products (Last 30 Days)");
        $this->newLine();

        $products = $service->getTrendingProducts($limit);

        if ($products->isEmpty()) {
            $this->warn("No trending products found.");
            return Command::SUCCESS;
        }

        $tableData = [];
        foreach ($products as $product) {
            $tableData[] = [
                'ID' => $product->ID_Products,
                'Name' => mb_substr($product->Name, 0, 40),
                'Brand' => $product->brand->name ?? '-',
                'Orders' => $product->trending_score ?? 0,
                'Rating Score' => ($product->rating_score ?? 0) . '%',
            ];
        }

        $this->table(array_keys($tableData[0]), $tableData);

        return Command::SUCCESS;
    }

    private function showTopRatedProducts(HybridRecommendationService $service, int $limit): int
    {
        $this->info("⭐ Top Rated Products");
        $this->newLine();

        $products = $service->getTopRatedProducts($limit, 1);

        if ($products->isEmpty()) {
            $this->warn("No rated products found.");
            return Command::SUCCESS;
        }

        $tableData = [];
        foreach ($products as $product) {
            $tableData[] = [
                'ID' => $product->ID_Products,
                'Name' => mb_substr($product->Name, 0, 40),
                'Brand' => $product->brand->name ?? '-',
                'Avg Rating' => round($product->reviews_avg_rating ?? 0, 1) . ' ⭐',
                'Reviews' => $product->reviews_count ?? 0,
            ];
        }

        $this->table(array_keys($tableData[0]), $tableData);

        return Command::SUCCESS;
    }

    private function showSearchResults(HybridRecommendationService $service, string $query, int $limit): int
    {
        $this->info("🔍 Search Results for: \"{$query}\"");
        $this->newLine();

        $products = $service->searchProducts($query, $limit);

        if ($products->isEmpty()) {
            $this->warn("No products found.");
            return Command::SUCCESS;
        }

        $tableData = [];
        foreach ($products as $product) {
            $tableData[] = [
                'ID' => $product->ID_Products,
                'Name' => mb_substr($product->Name, 0, 40),
                'Brand' => $product->brand->name ?? '-',
                'Search Score' => $product->search_score . '%',
                'TF-IDF' => ($product->relevance_score ?? 0) . '%',
            ];
        }

        $this->table(array_keys($tableData[0]), $tableData);

        return Command::SUCCESS;
    }

    private function showMenu(HybridRecommendationService $service, int $limit): void
    {
        $this->info("╔══════════════════════════════════════════════════════════╗");
        $this->info("║     🛍️  HYBRID RECOMMENDATION SYSTEM TESTER             ║");
        $this->info("╠══════════════════════════════════════════════════════════╣");
        $this->info("║                                                          ║");
        $this->info("║  Algorithm: TF-IDF + Rating + Popularity + Category      ║");
        $this->info("║                                                          ║");
        $this->info("╚══════════════════════════════════════════════════════════╝");
        $this->newLine();

        $this->line("Usage Examples:");
        $this->line("  php artisan recommendations:test --customer=1 --explain");
        $this->line("  php artisan recommendations:test --product=1");
        $this->line("  php artisan recommendations:test --trending");
        $this->line("  php artisan recommendations:test --top-rated");
        $this->line("  php artisan recommendations:test --search=\"nike running\"");
        $this->newLine();

        $this->info("📊 Current Algorithm Weights:");
        $this->table(
            ['Signal', 'Weight', 'Description'],
            [
                ['Content Similarity (TF-IDF)', '35%', 'Text-based product matching'],
                ['Rating Score', '25%', 'Bayesian average rating'],
                ['Popularity Score', '25%', 'Purchase frequency (log-scaled)'],
                ['Category Affinity', '15%', 'User preference learning'],
            ]
        );

        // Show available data
        $this->newLine();
        $this->info("📈 Database Stats:");
        $this->table(
            ['Entity', 'Count'],
            [
                ['Products', Product::count()],
                ['Customers', Customer::count()],
                ['Reviews', \App\Models\ProductReview::count()],
                ['Orders', \App\Models\Order::count()],
            ]
        );
    }
}
