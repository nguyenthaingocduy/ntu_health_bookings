<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Post extends Model
{
    use HasFactory, HasUuids;
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'title',
        'slug',
        'content',
        'featured_image',
        'excerpt',
        'author_id',
        'status',
        'is_featured',
        'published_at',
    ];
    
    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];
    
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'bg-gray-100 text-gray-800',
            'published' => 'bg-green-100 text-green-800',
            'archived' => 'bg-red-100 text-red-800',
        ];
        
        $statusText = [
            'draft' => 'Bản nháp',
            'published' => 'Đã xuất bản',
            'archived' => 'Đã lưu trữ',
        ];
        
        $class = $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
        $text = $statusText[$this->status] ?? 'Không xác định';
        
        return '<span class="px-2 py-1 rounded-full text-xs font-medium ' . $class . '">' . $text . '</span>';
    }
    
    public function getFormattedPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('d/m/Y H:i') : 'Chưa xuất bản';
    }
    
    public function getShortContentAttribute()
    {
        return $this->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($this->content), 150);
    }
    
    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ? asset($this->featured_image) : asset('images/default-post.jpg');
    }
    
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }
    
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
