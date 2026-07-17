<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'category_id',
        'description',
        'price',
        'qty',
        'unit'
    ];

    protected static function booted()
    {
        static::creating(function ($item) {
            if (empty($item->sku)) {
                $item->sku = static::generateSku();
            }
        });
    }

    /**
     * Generate a unique SKU for the item
     */
    public static function generateSku(): string
    {
        $prefix = 'BRG-' . date('Y') . '-';
        $latest = static::where('sku', 'like', $prefix . '%')
                        ->orderBy('sku', 'desc')
                        ->first();
        
        if ($latest) {
            $lastNum = substr($latest->sku, strlen($prefix));
            $sequence = intval($lastNum) + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the category that owns the item.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the stock histories for the item.
     */
    public function stockHistories(): HasMany
    {
        return $this->hasMany(StockHistory::class)->orderBy('created_at', 'desc');
    }
}
