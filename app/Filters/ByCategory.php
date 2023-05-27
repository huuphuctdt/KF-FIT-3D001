<?php 
    namespace App\Filters;

    class ByCategory{
        public function handle($request, \Closure $next) 
        { 
            $builder = $next($request); 
            if(request()->has('category')){ 
                return $builder->where('product_category_id', request()->query('category')); 
            } 
            return $builder; 
        }
    }
?>