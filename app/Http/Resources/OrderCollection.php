<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    private $pagination;

    public function __construct($resource)
    {
        $this->pagination = [
            'total' => $resource->total(),
            'count' => $resource->count(),
            'perPage' => $resource->perPage(),
            'currentPage' => $resource->currentPage(),
            'lastPage' => $resource->lastPage()
        ];
        parent::__construct($resource->getCollection());
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'pagination' => $this->pagination,
            'items' => $this->collection,
        ];
    }
}
