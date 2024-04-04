<?php

namespace Defrindr\Crudify\Resources;

use Illuminate\Http\Request;

class PaginationCollection extends CustomResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'items' => $this->resourceInstance::collection($this->collection),
            'meta' => [
                'currentPage' => $this->currentPage(),
                'total' => $this->total(),
                'perPage' => $this->perPage(),
                'path' => $this->path(),
                'totalPage' => ceil($this->total() / $this->perPage()),
            ],
        ];
    }
}
