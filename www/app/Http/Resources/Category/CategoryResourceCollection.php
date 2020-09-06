<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_map(function($item){
            return [
                'name' => $item['name'],
                'description' => $item['description']
            ];
        }, $this->collection->toArray());
//        return parent::toArray($request);
    }
}
