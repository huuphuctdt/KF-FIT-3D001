<?php 
    namespace App\Filters;

    class ByMinMax{
        public function handle($request, \Closure $next) 
        { 
            $builder = $next($request); 
            if(request()->has('min') && request()->has('max')){ 
                return $builder
                ->whereBetween('price', [request()->query('min'), request()->query('max')]); 
            } 
            return $builder; 
        }
    }
?>