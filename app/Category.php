<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    protected $fillable = [
        'name',
        'parent_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany('App\Product');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childrenCategories()
    {
        return $this->hasMany('App\Category', 'parent_id');
    }

    public function allProducts()
    {
        $query = "WITH RECURSIVE category_with_child AS
                   (SELECT id, name, parent_id
                    FROM categories
                    WHERE id = 7
                    UNION ALL
                    SELECT c_rec.id, c_rec.name, c_rec.parent_id
                    FROM categories c_rec
                             INNER JOIN category_with_child c ON c.id = c_rec.parent_id)
                  SELECT DISTINCT cp.product_id, p.name
                  FROM category_with_child cwc
                  INNER JOIN category_product cp ON cwc.id = cp.category_id
                  INNER JOIN products p on cp.product_id = p.id
                  ORDER BY cp.product_id;";

        $results = DB::select(DB::raw($query), array(
            'category_id' => $this->id,));

        $converted = [];
        foreach ($results as $result){
            $product = new Product;
            $product->forceFill((array) $result);
            $converted[] = $product;
        }

        return collect($converted);
    }
}
