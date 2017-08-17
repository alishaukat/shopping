<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    
    /*
     * Number of products for a given filter
     */
    public static function totalProducts($filters=array()){
        $products = self::setFilters($filters);
        return $products->count();
    }
    
    /*
     * Get All Products for given filters
     */
    public static function getAll($filters=array('limit'=>8, 'offset'=>0)){
        $products = self::setFilters($filters);
        
        if(!empty($filters['order_by']) && !empty($filters['order_by_type'])){
            $products = $products->orderBy($filters['order_by'], $filters['order_by_type']);
        }
        
        if(!empty($filters['offset'])){
            $products = $products->offset($filters['offset']);
        }
        
        if(!empty($filters['limit'])){
            $products = $products->limit($filters['limit']);
        }
        
        return $products->get();
    }
    
    /*
     * set product filters
     */
    private static function setFilters($filters){        
        $products = new Product();
        
        /* Global Search */
        if(!empty($filters['type']) && $filters['type'] == 'all'){
            $products = $products->where('name', 'like', "%".trim($filters['search'])."%");
            $products = $products->orWhere('description', 'like', "%".trim($filters['search'])."%");
            
        } else {
            
            if(!empty($filters['type']) && $filters['type'] == 'name'){
                $products = $products->where('name', 'like', "%".trim($filters['search'])."%");
            }
            if(!empty($filters['type']) && $filters['type'] == 'descrption'){
                $products = $products->where('descrption', 'like', "%".trim($filters['search'])."%");
            }
        }
        return $products;
    }    
}
