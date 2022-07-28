<?php

namespace App\Models;

use App\Models\User;
use App\Models\PostImage;
use App\Models\PostStatus;
use App\Models\PostComment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'post_status_id',
        'admin_id',
        'deleted',
        'note',
    ];

    /**
     * Get the user that owns the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /**
     * Get the status that owns the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(PostStatus::class, 'post_status_id');
    }
    /**
     * Get all of the images for the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(PostImage::class, 'post_id');
    }
    /**
     * Get all of the comments for the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(PostComment::class, 'post_id');
    }

    /**
     * Get all of the reacts for the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reacts()
    {
        return $this->hasMany(PostReact::class, 'post_id');
    }
}
