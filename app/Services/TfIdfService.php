<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class TfIdfService
{
    /**
     * Indonesian stop words to filter out
     */
    private array $stopWords = [
        'yang', 'dan', 'di', 'dari', 'ini', 'itu', 'untuk', 'dengan', 'pada', 'adalah',
        'ke', 'tidak', 'ya', 'ada', 'juga', 'akan', 'bisa', 'atau', 'lebih', 'sudah',
        'the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of',
        'with', 'by', 'from', 'up', 'about', 'into', 'through', 'during', 'before',
        'after', 'above', 'below', 'between', 'under', 'again', 'further', 'then',
        'once', 'here', 'there', 'when', 'where', 'why', 'how', 'all', 'each', 'few',
        'more', 'most', 'other', 'some', 'such', 'no', 'nor', 'not', 'only', 'own',
        'same', 'so', 'than', 'too', 'very', 'can', 'will', 'just', 'should', 'now',
        'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had',
        'having', 'do', 'does', 'did', 'doing', 'would', 'could', 'might', 'must',
        'shall', 'may', 'this', 'that', 'these', 'those', 'it', 'its'
    ];

    /**
     * Tokenize text into words
     */
    public function tokenize(string $text): array
    {
        // Convert to lowercase and remove special characters
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text);
        
        // Split into words
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        // Filter stop words and short words
        return array_values(array_filter($words, function($word) {
            return strlen($word) >= 2 && !in_array($word, $this->stopWords);
        }));
    }

    /**
     * Calculate Term Frequency (TF) for a document
     */
    public function calculateTf(array $tokens): array
    {
        $tf = [];
        $totalTerms = count($tokens);
        
        if ($totalTerms === 0) {
            return [];
        }
        
        $termCounts = array_count_values($tokens);
        
        foreach ($termCounts as $term => $count) {
            $tf[$term] = $count / $totalTerms;
        }
        
        return $tf;
    }

    /**
     * Calculate Inverse Document Frequency (IDF) for all documents
     */
    public function calculateIdf(array $allDocumentTokens): array
    {
        $idf = [];
        $totalDocuments = count($allDocumentTokens);
        
        if ($totalDocuments === 0) {
            return [];
        }
        
        // Get unique terms across all documents
        $allTerms = [];
        foreach ($allDocumentTokens as $tokens) {
            $allTerms = array_merge($allTerms, array_unique($tokens));
        }
        $allTerms = array_unique($allTerms);
        
        // Calculate IDF for each term
        foreach ($allTerms as $term) {
            $documentCount = 0;
            foreach ($allDocumentTokens as $tokens) {
                if (in_array($term, $tokens)) {
                    $documentCount++;
                }
            }
            // Using smoothed IDF: log((N + 1) / (df + 1)) + 1
            $idf[$term] = log(($totalDocuments + 1) / ($documentCount + 1)) + 1;
        }
        
        return $idf;
    }

    /**
     * Calculate TF-IDF vector for a document
     */
    public function calculateTfIdf(array $tf, array $idf): array
    {
        $tfIdf = [];
        
        foreach ($tf as $term => $tfValue) {
            $idfValue = $idf[$term] ?? 1;
            $tfIdf[$term] = $tfValue * $idfValue;
        }
        
        return $tfIdf;
    }

    /**
     * Calculate cosine similarity between two TF-IDF vectors
     */
    public function cosineSimilarity(array $vector1, array $vector2): float
    {
        // Get all unique terms
        $allTerms = array_unique(array_merge(array_keys($vector1), array_keys($vector2)));
        
        $dotProduct = 0;
        $magnitude1 = 0;
        $magnitude2 = 0;
        
        foreach ($allTerms as $term) {
            $v1 = $vector1[$term] ?? 0;
            $v2 = $vector2[$term] ?? 0;
            
            $dotProduct += $v1 * $v2;
            $magnitude1 += $v1 * $v1;
            $magnitude2 += $v2 * $v2;
        }
        
        $magnitude1 = sqrt($magnitude1);
        $magnitude2 = sqrt($magnitude2);
        
        if ($magnitude1 == 0 || $magnitude2 == 0) {
            return 0;
        }
        
        return $dotProduct / ($magnitude1 * $magnitude2);
    }

    /**
     * Get similar products using TF-IDF cosine similarity
     */
    public function getSimilarProducts(Product $product, int $limit = 4): Collection
    {
        // Get all products with their relationships
        $allProducts = Product::with(['brand', 'category', 'subcategory', 'gender', 'variants'])
            ->where('ID_Products', '!=', $product->ID_Products)
            ->get();
        
        if ($allProducts->isEmpty()) {
            return collect([]);
        }
        
        // Prepare documents (including the target product)
        $documents = [];
        $productTokens = [];
        
        // Target product
        $targetContent = $product->getTfIdfContent();
        $targetTokens = $this->tokenize($targetContent);
        
        // All other products
        foreach ($allProducts as $p) {
            $content = $p->getTfIdfContent();
            $tokens = $this->tokenize($content);
            $documents[$p->ID_Products] = $tokens;
            $productTokens[] = $tokens;
        }
        
        // Add target tokens for IDF calculation
        $productTokens[] = $targetTokens;
        
        // Calculate IDF across all documents
        $idf = $this->calculateIdf($productTokens);
        
        // Calculate TF-IDF for target product
        $targetTf = $this->calculateTf($targetTokens);
        $targetTfIdf = $this->calculateTfIdf($targetTf, $idf);
        
        // Calculate similarity scores
        $similarities = [];
        foreach ($documents as $productId => $tokens) {
            $tf = $this->calculateTf($tokens);
            $tfIdf = $this->calculateTfIdf($tf, $idf);
            $similarity = $this->cosineSimilarity($targetTfIdf, $tfIdf);
            $similarities[$productId] = $similarity;
        }
        
        // Sort by similarity (descending)
        arsort($similarities);
        
        // Get top N product IDs
        $topProductIds = array_slice(array_keys($similarities), 0, $limit);
        
        // Return products in order of similarity
        $sortedProducts = collect();
        foreach ($topProductIds as $id) {
            $foundProduct = $allProducts->firstWhere('ID_Products', $id);
            if ($foundProduct) {
                $foundProduct->similarity_score = round($similarities[$id] * 100, 2);
                $sortedProducts->push($foundProduct);
            }
        }
        
        return $sortedProducts;
    }

    /**
     * Get product recommendations based on search query
     */
    public function searchProducts(string $query, int $limit = 10): Collection
    {
        if (empty(trim($query))) {
            return collect([]);
        }
        
        // Get all products
        $allProducts = Product::with(['brand', 'category', 'subcategory', 'gender', 'variants.images'])
            ->get();
        
        if ($allProducts->isEmpty()) {
            return collect([]);
        }
        
        // Prepare documents
        $documents = [];
        $productTokens = [];
        
        foreach ($allProducts as $product) {
            $content = $product->getTfIdfContent();
            $tokens = $this->tokenize($content);
            $documents[$product->ID_Products] = $tokens;
            $productTokens[] = $tokens;
        }
        
        // Tokenize search query
        $queryTokens = $this->tokenize($query);
        $productTokens[] = $queryTokens;
        
        // Calculate IDF
        $idf = $this->calculateIdf($productTokens);
        
        // Calculate TF-IDF for query
        $queryTf = $this->calculateTf($queryTokens);
        $queryTfIdf = $this->calculateTfIdf($queryTf, $idf);
        
        // Calculate similarity scores
        $similarities = [];
        foreach ($documents as $productId => $tokens) {
            $tf = $this->calculateTf($tokens);
            $tfIdf = $this->calculateTfIdf($tf, $idf);
            $similarity = $this->cosineSimilarity($queryTfIdf, $tfIdf);
            $similarities[$productId] = $similarity;
        }
        
        // Sort by similarity (descending)
        arsort($similarities);
        
        // Filter products with similarity > 0
        $topProducts = collect();
        foreach ($similarities as $id => $score) {
            if ($score > 0 && $topProducts->count() < $limit) {
                $product = $allProducts->firstWhere('ID_Products', $id);
                if ($product) {
                    $product->relevance_score = round($score * 100, 2);
                    $topProducts->push($product);
                }
            }
        }
        
        return $topProducts;
    }

    /**
     * Get recommendations based on customer order history
     */
    public function getPersonalizedRecommendations(int $customerId, int $limit = 4): Collection
    {
        // Get products from customer's order history
        $orderedProducts = Product::with(['brand', 'category', 'subcategory', 'gender', 'variants'])
            ->whereHas('variants.cartItems', function($query) use ($customerId) {
                $query->where('ID_Customers', $customerId);
            })
            ->orWhereHas('variants', function($query) use ($customerId) {
                $query->whereHas('cartItems', function($q) use ($customerId) {
                    $q->where('ID_Customers', $customerId);
                });
            })
            ->get();
        
        if ($orderedProducts->isEmpty()) {
            // Return random products if no history
            return Product::with(['brand', 'category', 'subcategory', 'gender', 'variants.images'])
                ->inRandomOrder()
                ->limit($limit)
                ->get();
        }
        
        // Combine all ordered products' content
        $combinedContent = '';
        foreach ($orderedProducts as $product) {
            $combinedContent .= ' ' . $product->getTfIdfContent();
        }
        
        // Get products not in order history
        $orderedIds = $orderedProducts->pluck('ID_Products')->toArray();
        $candidateProducts = Product::with(['brand', 'category', 'subcategory', 'gender', 'variants.images'])
            ->whereNotIn('ID_Products', $orderedIds)
            ->get();
        
        if ($candidateProducts->isEmpty()) {
            return collect([]);
        }
        
        // Prepare documents
        $documents = [];
        $productTokens = [];
        
        foreach ($candidateProducts as $product) {
            $content = $product->getTfIdfContent();
            $tokens = $this->tokenize($content);
            $documents[$product->ID_Products] = $tokens;
            $productTokens[] = $tokens;
        }
        
        // Tokenize combined history
        $historyTokens = $this->tokenize($combinedContent);
        $productTokens[] = $historyTokens;
        
        // Calculate IDF
        $idf = $this->calculateIdf($productTokens);
        
        // Calculate TF-IDF for history
        $historyTf = $this->calculateTf($historyTokens);
        $historyTfIdf = $this->calculateTfIdf($historyTf, $idf);
        
        // Calculate similarity scores
        $similarities = [];
        foreach ($documents as $productId => $tokens) {
            $tf = $this->calculateTf($tokens);
            $tfIdf = $this->calculateTfIdf($tf, $idf);
            $similarity = $this->cosineSimilarity($historyTfIdf, $tfIdf);
            $similarities[$productId] = $similarity;
        }
        
        // Sort by similarity
        arsort($similarities);
        
        // Get top products
        $topProducts = collect();
        foreach ($similarities as $id => $score) {
            if ($topProducts->count() >= $limit) break;
            
            $product = $candidateProducts->firstWhere('ID_Products', $id);
            if ($product) {
                $product->recommendation_score = round($score * 100, 2);
                $topProducts->push($product);
            }
        }
        
        return $topProducts;
    }
}
