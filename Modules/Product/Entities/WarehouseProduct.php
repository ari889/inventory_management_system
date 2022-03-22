<?php

namespace Modules\Product\Entities;

use Modules\Base\Entities\BaseModel;

class WarehouseProduct extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'warehouse_products';

    protected $fillable = ['warehouse_id', 'product_id', 'qty'];
}
