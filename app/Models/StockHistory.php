<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockHistory extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'type', 'qty', 'notes'];

    /**
     * Get the item that owns the stock history.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
