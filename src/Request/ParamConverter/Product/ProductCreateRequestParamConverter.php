<?php

namespace App\Request\ParamConverter\Product;

use App\Request\ParamConverter\AbstractJsonRequestParamConverter;

class ProductCreateRequestParamConverter extends AbstractJsonRequestParamConverter
{
    public function getProperty(): string
    {
        return 'productCreateRequest';
    }
}
