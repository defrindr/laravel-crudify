<?php

namespace Defrindr\Crudify\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomResourceCollection extends ResourceCollection
{
    /**
     * Initialize custom resource
     */
    public $resourceInstance;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $instance)
    {
        parent::__construct($resource);

        $this->resourceInstance = $instance;
        $this->resource = $this->collectResource($resource);
    }
}
