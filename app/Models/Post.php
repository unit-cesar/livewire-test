<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Policies\PostPolicy;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'is_published',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_published' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'excerpt',
        'published_at_formatted',
    ];

    /**
     * The policy associated with the model.
     *
     * @var string
     */
    protected $policies = [
        PostPolicy::class,
    ];

    /**
     * Relationship with the User model.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the excerpt attribute.
     *
     * @return string
     */
    public function getExcerptAttribute(): string
    {
        return str()->limit(strip_tags($this->content), 100);
    }

    /**
     * Get the formatted published at attribute.
     *
     * @return string|null
     */
    public function getPublishedAtFormattedAttribute(): ?string
    {
        return $this->created_at?->format('d/m/Y H:i');
    }

    /**
     * Scope a query to only include published posts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to only include drafts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDrafts($query)
    {
        return $query->where('is_published', false);
    }

    /**
     * Scope a query to search posts by title or content.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        });
    }

    /**
     * Determine if the post is published.
     *
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->is_published;
    }

    /**
     * Publish the post.
     *
     * @return bool
     */
    public function publish(): bool
    {
        return $this->update(['is_published' => true]);
    }

    /**
     * Unpublish the post.
     *
     * @return bool
     */
    public function unpublish(): bool
    {
        return $this->update(['is_published' => false]);
    }
}
