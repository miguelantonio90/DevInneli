<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleComposite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cant', 'price', 'article_id', 'composite_id',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Articles::class);
    }
}
