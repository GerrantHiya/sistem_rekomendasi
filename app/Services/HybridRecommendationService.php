<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Hybrid Recommendation Service
 * 
 * Combines multiple recommendation signals using a weighted ensemble approach
 * (similar to Random Forest voting mechanism):
 * 
 * 1. TF-IDF Content Similarity (content-based filtering)
 * 2. Rating Score (quality-based ranking)
 * 3. Purchase Popularity (collaborative filtering)
 * 4. Category Affinity (user preference learning)
 * 
 * Final Score = Σ(weight_i × normalized_score_i)
 */
class HybridRecommendationService
{
    private TfIdfService $tfIdfService;

    /**
     * Default weights for each signal (can be tuned)
     * These weights act similar to feature importance in Random Forest
     */
    private array $weights = [
        'content_similarity' => 0.35,  // TF-IDF similarity
        'rating_score' => 0.25,         // Average rating & count
        'popularity_score' => 0.25,     // Purchase frequency
        'category_affinity' => 0.15,    // User's category preferences
    ];

    /**
     * Cache duration in seconds (1 hour)
     */
    private int $cacheDuration = 3600;

    public function __construct(TfIdfService $tfIdfService)
    {
        $this->tfIdfService = $tfIdfService;
    }

    /**
     * Set custom weights for the ensemble
     */
    public function setWeights(array $weights): self
    {
        $this->weights = array_merge($this->weights, $weights);
        return $this;
    }

    /**
     * Get personalized recommendations for a customer
     * Uses hybrid approach combining all signals
     */
    public function getPersonalizedRecommendations(int $customerId, int $limit = 8): Collection
    {
        $cacheKey = "recommendations_user_{$customerId}_{$limit}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($customerId, $limit) {
            return $this->calculatePersonalizedRecommendations($customerId, $limit);
        });
    }

    /**
     * Calculate personalized recommendations (not cached)
     */
    private function calculatePersonalizedRecommendations(int $customerId, int $limit): Collection
    {
        // Get user's purchase history
        $purchasedProductIds = $this->getUserPurchasedProductIds($customerId);
        
        // Get all candidate products (not purchased yet)
        $candidateProducts = Product::with(['brand', 'category', 'subcategory', 'gender', 'variants.images', 'reviews'])
            ->when($purchasedProductIds->isNotEmpty(), function ($query) use ($purchasedProductIds) {
                $query->whereNotIn('ID_Products', $purchasedProductIds);
            })
            ->get();

        if ($candidateProducts->isEmpty()) {
            return collect([]);
        }

        // Get global statistics for normalization
        $globalStats = $this->getGlobalProductStats();
        
        // Get user's category preferences
        $categoryAffinities = $this->getUserCategoryAffinities($customerId);
        
        // Get user's purchase history content for TF-IDF
        $userProfileContent = $this->buildUserProfileContent($customerId);

        // Calculate scores for each product
        $scoredProducts = [];
        
        foreach ($candidateProducts as $product) {
            $scores = [
                'content_similarity' => $this->calculateContentSimilarity($product, $userProfileContent, $candidateProducts),
                'rating_score' => $this->calculateRatingScore($product, $globalStats),
                'popularity_score' => $this->calculatePopularityScore($product, $globalStats),
                'category_affinity' => $this->calculateCategoryAffinity($product, $categoryAffinities),
            ];

            // Weighted ensemble score
            $finalScore = 0;
            foreach ($scores as $signal => $score) {
                $finalScore += $this->weights[$signal] * $score;
            }

            $product->recommendation_score = round($finalScore * 100, 2);
            $product->score_breakdown = $scores;
            $scoredProducts[] = $product;
        }

        // Sort by final score (descending)
        usort($scoredProducts, fn($a, $b) => $b->recommendation_score <=> $a->recommendation_score);

        return collect(array_slice($scoredProducts, 0, $limit));
    }

    /**
     * Get similar products with hybrid scoring
     */
    public function getSimilarProducts(Product $product, int $limit = 4): Collection
    {
        $cacheKey = "similar_products_{$product->ID_Products}_{$limit}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($product, $limit) {
            return $this->calculateSimilarProducts($product, $limit);
        });
    }

    /**
     * Calculate similar products (not cached)
     */
    private function calculateSimilarProducts(Product $product, int $limit): Collection
    {
        // Get all other products
        $candidateProducts = Product::with(['brand', 'category', 'subcategory', 'gender', 'variants.images', 'reviews'])
            ->where('ID_Products', '!=', $product->ID_Products)
            ->get();

        if ($candidateProducts->isEmpty()) {
            return collect([]);
        }

        $globalStats = $this->getGlobalProductStats();
        $targetContent = $product->getTfIdfContent();

        $scoredProducts = [];

        foreach ($candidateProducts as $candidate) {
            // For similar products, weight content similarity higher
            $contentScore = $this->calculateTfIdfSimilarity($targetContent, $candidate->getTfIdfContent(), $candidateProducts);
            $ratingScore = $this->calculateRatingScore($candidate, $globalStats);
            $popularityScore = $this->calculatePopularityScore($candidate, $globalStats);
            
            // Same category/brand bonus
            $categoryBonus = ($candidate->ID_Categories === $product->ID_Categories) ? 0.1 : 0;
            $brandBonus = ($candidate->ID_Brand === $product->ID_Brand) ? 0.05 : 0;

            // Weighted score with emphasis on content similarity for "similar products"
            $finalScore = (0.50 * $contentScore) + 
                          (0.20 * $ratingScore) + 
                          (0.15 * $popularityScore) + 
                          $categoryBonus + 
                          $brandBonus;

            $candidate->similarity_score = round($finalScore * 100, 2);
            $candidate->content_match = round($contentScore * 100, 2);
            $scoredProducts[] = $candidate;
        }

        usort($scoredProducts, fn($a, $b) => $b->similarity_score <=> $a->similarity_score);

        return collect(array_slice($scoredProducts, 0, $limit));
    }

    /**
     * Get trending/popular products
     */
    public function getTrendingProducts(int $limit = 8, int $days = 30): Collection
    {
        $cacheKey = "trending_products_{$limit}_{$days}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($limit, $days) {
            $startDate = now()->subDays($days);

            // Get products ordered in the last N days with order counts
            $trendingIds = OrderItem::join('product_variants', 'order_items.ID_Variant', '=', 'product_variants.ID_Variants')
                ->join('orders', 'order_items.ID_Orders', '=', 'orders.ID_Orders')
                ->where('orders.place_at', '>=', $startDate)
                ->whereIn('orders.Status', [Order::STATUS_PROCESSING, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED])
                ->select('product_variants.ID_Product', DB::raw('COUNT(*) as order_count'))
                ->groupBy('product_variants.ID_Product')
                ->orderByDesc('order_count')
                ->limit($limit * 2)
                ->pluck('order_count', 'ID_Product');

            if ($trendingIds->isEmpty()) {
                // Fallback to highest rated products
                return $this->getTopRatedProducts($limit);
            }

            $products = Product::with(['brand', 'category', 'subcategory', 'gender', 'variants.images', 'reviews'])
                ->whereIn('ID_Products', $trendingIds->keys())
                ->get();

            // Add trending metadata
            $globalStats = $this->getGlobalProductStats();
            
            foreach ($products as $product) {
                $orderCount = $trendingIds[$product->ID_Products] ?? 0;
                $product->trending_score = $orderCount;
                $product->rating_score = round($this->calculateRatingScore($product, $globalStats) * 100, 2);
            }

            return $products
                ->sortByDesc('trending_score')
                ->take($limit)
                ->values();
        });
    }

    /**
     * Get top rated products
     */
    public function getTopRatedProducts(int $limit = 8, int $minReviews = 1): Collection
    {
        $cacheKey = "top_rated_products_{$limit}_{$minReviews}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($limit, $minReviews) {
            return Product::with(['brand', 'category', 'subcategory', 'gender', 'variants.images', 'reviews'])
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->having('reviews_count', '>=', $minReviews)
                ->orderByDesc('reviews_avg_rating')
                ->orderByDesc('reviews_count')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Search products with hybrid ranking
     */
    public function searchProducts(string $query, int $limit = 20): Collection
    {
        $results = $this->tfIdfService->searchProducts($query, $limit * 2);
        
        if ($results->isEmpty()) {
            return collect([]);
        }

        $globalStats = $this->getGlobalProductStats();

        foreach ($results as $product) {
            $tfIdfScore = ($product->relevance_score ?? 0) / 100;
            $ratingScore = $this->calculateRatingScore($product, $globalStats);
            $popularityScore = $this->calculatePopularityScore($product, $globalStats);

            // For search, TF-IDF relevance is most important
            $finalScore = (0.60 * $tfIdfScore) + 
                          (0.25 * $ratingScore) + 
                          (0.15 * $popularityScore);

            $product->search_score = round($finalScore * 100, 2);
        }

        return $results
            ->sortByDesc('search_score')
            ->take($limit)
            ->values();
    }

    /**
     * Clear recommendation cache for a user
     */
    public function clearUserCache(int $customerId): void
    {
        Cache::forget("recommendations_user_{$customerId}_4");
        Cache::forget("recommendations_user_{$customerId}_8");
        Cache::forget("recommendations_user_{$customerId}_12");
    }

    /**
     * Clear all recommendation caches
     */
    public function clearAllCache(): void
    {
        Cache::flush(); // Be careful in production - might want to use tags instead
    }

    // ==================== PRIVATE HELPER METHODS ====================

    /**
     * Get product IDs purchased by a user
     */
    private function getUserPurchasedProductIds(int $customerId): Collection
    {
        return OrderItem::join('product_variants', 'order_items.ID_Variant', '=', 'product_variants.ID_Variants')
            ->join('orders', 'order_items.ID_Orders', '=', 'orders.ID_Orders')
            ->where('orders.ID_Customers', $customerId)
            ->whereIn('orders.Status', [Order::STATUS_PROCESSING, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED])
            ->distinct()
            ->pluck('product_variants.ID_Product');
    }

    /**
     * Calculate global product statistics for normalization
     */
    private function getGlobalProductStats(): array
    {
        $cacheKey = 'global_product_stats';
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () {
            // Purchase counts per product
            $purchaseCounts = OrderItem::join('product_variants', 'order_items.ID_Variant', '=', 'product_variants.ID_Variants')
                ->join('orders', 'order_items.ID_Orders', '=', 'orders.ID_Orders')
                ->whereIn('orders.Status', [Order::STATUS_PROCESSING, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED])
                ->select('product_variants.ID_Product', DB::raw('COUNT(*) as purchase_count'))
                ->groupBy('product_variants.ID_Product')
                ->pluck('purchase_count', 'ID_Product')
                ->toArray();

            $maxPurchases = empty($purchaseCounts) ? 1 : max($purchaseCounts);

            // Rating stats per product
            $ratingStats = ProductReview::approved()
                ->select('ID_Products', 
                    DB::raw('AVG(rating) as avg_rating'),
                    DB::raw('COUNT(*) as review_count'))
                ->groupBy('ID_Products')
                ->get()
                ->keyBy('ID_Products')
                ->toArray();

            $maxReviewCount = empty($ratingStats) ? 1 : max(array_column($ratingStats, 'review_count'));

            return [
                'purchase_counts' => $purchaseCounts,
                'max_purchases' => $maxPurchases,
                'rating_stats' => $ratingStats,
                'max_review_count' => $maxReviewCount,
            ];
        });
    }

    /**
     * Get user's category preferences based on purchase history
     */
    private function getUserCategoryAffinities(int $customerId): array
    {
        $categoryCounts = OrderItem::join('product_variants', 'order_items.ID_Variant', '=', 'product_variants.ID_Variants')
            ->join('products', 'product_variants.ID_Product', '=', 'products.ID_Products')
            ->join('orders', 'order_items.ID_Orders', '=', 'orders.ID_Orders')
            ->where('orders.ID_Customers', $customerId)
            ->whereIn('orders.Status', [Order::STATUS_PROCESSING, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED])
            ->select('products.ID_Categories', DB::raw('COUNT(*) as count'))
            ->groupBy('products.ID_Categories')
            ->pluck('count', 'ID_Categories')
            ->toArray();

        if (empty($categoryCounts)) {
            return [];
        }

        $maxCount = max($categoryCounts);
        
        // Normalize to 0-1 range
        return array_map(fn($count) => $count / $maxCount, $categoryCounts);
    }

    /**
     * Build user profile content from purchase history for TF-IDF
     */
    private function buildUserProfileContent(int $customerId): string
    {
        $purchasedProducts = Product::with(['brand', 'category', 'subcategory', 'gender', 'variants'])
            ->whereHas('variants', function ($query) use ($customerId) {
                $query->whereHas('orderItems', function ($q) use ($customerId) {
                    $q->whereHas('order', function ($orderQuery) use ($customerId) {
                        $orderQuery->where('ID_Customers', $customerId)
                            ->whereIn('Status', [Order::STATUS_PROCESSING, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED]);
                    });
                });
            })
            ->get();

        $content = '';
        foreach ($purchasedProducts as $product) {
            // Weight recent purchases more (could add timestamp weighting here)
            $content .= ' ' . $product->getTfIdfContent();
        }

        return $content;
    }

    /**
     * Calculate content similarity using TF-IDF
     */
    private function calculateContentSimilarity(Product $product, string $userProfileContent, Collection $allProducts): float
    {
        if (empty(trim($userProfileContent))) {
            return 0.5; // Neutral score for new users
        }

        // Prepare documents for TF-IDF
        $documents = [];
        $productTokens = [];

        foreach ($allProducts as $p) {
            $tokens = $this->tfIdfService->tokenize($p->getTfIdfContent());
            $documents[$p->ID_Products] = $tokens;
            $productTokens[] = $tokens;
        }

        // Add user profile
        $userTokens = $this->tfIdfService->tokenize($userProfileContent);
        $productTokens[] = $userTokens;

        // Calculate IDF
        $idf = $this->tfIdfService->calculateIdf($productTokens);

        // Calculate TF-IDF for user profile
        $userTf = $this->tfIdfService->calculateTf($userTokens);
        $userTfIdf = $this->tfIdfService->calculateTfIdf($userTf, $idf);

        // Calculate similarity for target product
        if (!isset($documents[$product->ID_Products])) {
            return 0;
        }

        $productTf = $this->tfIdfService->calculateTf($documents[$product->ID_Products]);
        $productTfIdf = $this->tfIdfService->calculateTfIdf($productTf, $idf);

        return $this->tfIdfService->cosineSimilarity($userTfIdf, $productTfIdf);
    }

    /**
     * Calculate TF-IDF similarity between two content strings
     */
    private function calculateTfIdfSimilarity(string $content1, string $content2, Collection $allProducts): float
    {
        $tokens1 = $this->tfIdfService->tokenize($content1);
        $tokens2 = $this->tfIdfService->tokenize($content2);

        // Build corpus for IDF
        $allTokens = [$tokens1, $tokens2];
        foreach ($allProducts as $p) {
            $allTokens[] = $this->tfIdfService->tokenize($p->getTfIdfContent());
        }

        $idf = $this->tfIdfService->calculateIdf($allTokens);

        $tf1 = $this->tfIdfService->calculateTf($tokens1);
        $tfIdf1 = $this->tfIdfService->calculateTfIdf($tf1, $idf);

        $tf2 = $this->tfIdfService->calculateTf($tokens2);
        $tfIdf2 = $this->tfIdfService->calculateTfIdf($tf2, $idf);

        return $this->tfIdfService->cosineSimilarity($tfIdf1, $tfIdf2);
    }

    /**
     * Calculate rating score (0-1)
     * Uses Bayesian average to handle products with few reviews
     */
    private function calculateRatingScore(Product $product, array $globalStats): float
    {
        $stats = $globalStats['rating_stats'][$product->ID_Products] ?? null;

        if (!$stats) {
            return 0.5; // Neutral score for unrated products
        }

        $avgRating = $stats['avg_rating'] ?? 0;
        $reviewCount = $stats['review_count'] ?? 0;
        $maxReviewCount = $globalStats['max_review_count'];

        // Bayesian average: (R × v + C × m) / (v + m)
        // R = item's average rating
        // v = number of votes for the item
        // C = mean vote across all items (assume 3.0 for 1-5 scale)
        // m = minimum votes required (smoothing factor)
        $C = 3.0;
        $m = 3; // Minimum votes before trusting the rating

        $bayesianRating = (($avgRating * $reviewCount) + ($C * $m)) / ($reviewCount + $m);

        // Normalize to 0-1 (rating is 1-5)
        $normalizedRating = ($bayesianRating - 1) / 4;

        // Also factor in review count (popular products get slight boost)
        $popularityFactor = min(1, $reviewCount / ($maxReviewCount * 0.5));
        
        // Combine: 80% rating, 20% popularity
        return (0.8 * $normalizedRating) + (0.2 * $popularityFactor);
    }

    /**
     * Calculate popularity score based on purchase frequency (0-1)
     */
    private function calculatePopularityScore(Product $product, array $globalStats): float
    {
        $purchaseCount = $globalStats['purchase_counts'][$product->ID_Products] ?? 0;
        $maxPurchases = $globalStats['max_purchases'];

        if ($maxPurchases == 0) {
            return 0.5;
        }

        // Use logarithmic scaling to prevent very popular items from dominating
        $normalizedScore = log1p($purchaseCount) / log1p($maxPurchases);

        return min(1, $normalizedScore);
    }

    /**
     * Calculate category affinity score based on user's preferences (0-1)
     */
    private function calculateCategoryAffinity(Product $product, array $categoryAffinities): float
    {
        if (empty($categoryAffinities)) {
            return 0.5; // Neutral for new users
        }

        return $categoryAffinities[$product->ID_Categories] ?? 0;
    }

    /**
     * Get recommendation explanation for debugging/transparency
     */
    public function explainRecommendation(Product $product, int $customerId): array
    {
        $globalStats = $this->getGlobalProductStats();
        $categoryAffinities = $this->getUserCategoryAffinities($customerId);
        $userProfileContent = $this->buildUserProfileContent($customerId);

        $candidateProducts = Product::with(['brand', 'category', 'subcategory', 'gender', 'variants'])
            ->get();

        return [
            'product_id' => $product->ID_Products,
            'product_name' => $product->Name,
            'scores' => [
                'content_similarity' => [
                    'value' => $this->calculateContentSimilarity($product, $userProfileContent, $candidateProducts),
                    'weight' => $this->weights['content_similarity'],
                    'description' => 'How well this product matches your purchase history (TF-IDF)',
                ],
                'rating_score' => [
                    'value' => $this->calculateRatingScore($product, $globalStats),
                    'weight' => $this->weights['rating_score'],
                    'description' => 'Product rating and review quality (Bayesian average)',
                ],
                'popularity_score' => [
                    'value' => $this->calculatePopularityScore($product, $globalStats),
                    'weight' => $this->weights['popularity_score'],
                    'description' => 'How often this product is purchased',
                ],
                'category_affinity' => [
                    'value' => $this->calculateCategoryAffinity($product, $categoryAffinities),
                    'weight' => $this->weights['category_affinity'],
                    'description' => 'How much you like this category',
                ],
            ],
            'weights' => $this->weights,
        ];
    }
}
