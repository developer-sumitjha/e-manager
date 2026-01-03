<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToTenant;

class Product extends Model
{
    use BelongsToTenant, SoftDeletes, HasFactory;
    protected $fillable = [
        'tenant_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'sku',
        'stock',
        'stock_quantity',
        'low_stock_threshold',
        'track_inventory',
        'allow_backorders',
        'stock_status',
        'image',
        'images',
        'video',
        'primary_image_index',
        'is_active',
        'is_featured'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'track_inventory' => 'boolean',
        'allow_backorders' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'images' => 'array',
        'primary_image_index' => 'integer',
    ];
    
    protected $appends = ['primary_image_url', 'all_images_urls', 'video_url'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    /**
     * Get the primary image URL
     */
    public function getPrimaryImageUrlAttribute()
    {
        if ($this->images && is_array($this->images) && count($this->images) > 0) {
            $index = $this->primary_image_index ?? 0;
            $image = $this->images[$index] ?? $this->images[0];
            return asset('storage/' . $image);
        }
        
        // Fallback to old single image field
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        return null;
    }
    
    /**
     * Get all images URLs
     */
    public function getAllImagesUrlsAttribute()
    {
        if ($this->images && is_array($this->images)) {
            return array_map(function($image) {
                return asset('storage/' . $image);
            }, $this->images);
        }
        
        // Fallback to old single image
        if ($this->image) {
            return [asset('storage/' . $this->image)];
        }
        
        return [];
    }
    
    /**
     * Get the video URL
     */
    public function getVideoUrlAttribute()
    {
        return $this->video ? asset('storage/' . $this->video) : null;
    }
    
    /**
     * Get thumbnail for lists (first/primary image)
     */
    public function getThumbnailAttribute()
    {
        return $this->primary_image_url;
    }
    
    /**
     * Check if product is in stock
     */
    public function isInStock()
    {
        if (!$this->track_inventory) {
            return true;
        }
        
        return $this->stock_quantity > 0 || ($this->allow_backorders && $this->stock_status === 'on_backorder');
    }
    
    /**
     * Check if product is low stock
     */
    public function isLowStock()
    {
        return $this->track_inventory && $this->stock_quantity <= $this->low_stock_threshold;
    }
    
    /**
     * Reduce stock quantity
     */
    public function reduceStock($quantity)
    {
        if (!$this->track_inventory) {
            return true;
        }
        
        if ($this->stock_quantity >= $quantity) {
            $this->stock_quantity -= $quantity;
            $this->updateStockStatus();
            return $this->save();
        }
        
        return false;
    }
    
    /**
     * Update stock status based on current quantity
     */
    public function updateStockStatus()
    {
        if (!$this->track_inventory) {
            return;
        }
        
        if ($this->stock_quantity <= 0) {
            $this->stock_status = $this->allow_backorders ? 'on_backorder' : 'out_of_stock';
        } else {
            $this->stock_status = 'in_stock';
        }
        
        $this->save();
    }
}
