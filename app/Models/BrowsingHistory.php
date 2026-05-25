<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrowsingHistory extends Model
{
    protected $table = 'browsing_history';

    protected $fillable = ['user_id', 'product_id', 'category_id', 'interaction_type', 'interaction_score'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
