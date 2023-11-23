<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportPosts extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'reportposts';
    protected $fillable = [
        'post_id', 'author', 'user_id'
    ];

/**
     * Get the user that owns the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function writer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }

    /**
     * Get the post that the report belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    /**
     * Get the user who reported the post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    /**
     * Get all of the comments for the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

}
