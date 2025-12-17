<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\IdHasher;

class Size extends Model
{
    protected $table = 'size';
    protected $primaryKey = 'ID_Size';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'chest',
        'body_length',
        'waist',
        'hip',
        'thigh'
    ];

    /**
     * Get variants with this size
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'ID_Size', 'ID_Size');
    }

    /**
     * Get encoded hash ID
     */
    public function getHashIdAttribute(): string
    {
        return app(IdHasher::class)->encode($this->ID_Size);
    }

    /**
     * Find by hash
     */
    public static function findByHash(string $hash): ?self
    {
        $id = app(IdHasher::class)->decode($hash);
        return $id ? static::find($id) : null;
    }

    /**
     * Get formatted measurements for display
     */
    public function getMeasurementsAttribute(): string
    {
        $measurements = [];
        
        if ($this->chest) $measurements[] = "Chest: {$this->chest}cm";
        if ($this->body_length) $measurements[] = "Length: {$this->body_length}cm";
        if ($this->waist) $measurements[] = "Waist: {$this->waist}cm";
        if ($this->hip) $measurements[] = "Hip: {$this->hip}cm";
        if ($this->thigh) $measurements[] = "Thigh: {$this->thigh}cm";
        
        return implode(' | ', $measurements);
    }

    /**
     * Check if this is a "One Size" or N/A size
     */
    public function getIsOneSizeAttribute(): bool
    {
        return in_array(strtoupper($this->name), ['ONE SIZE', 'OS', 'N/A', 'FREE']);
    }
}
