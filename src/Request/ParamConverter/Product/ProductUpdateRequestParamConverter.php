<?php

namespace App\Request\ParamConverter\Product;

use App\Request\ParamConverter\AbstractJsonRequestParamConverter;

class ProductUpdateRequestParamConverter extends AbstractJsonRequestParamConverter
{
    public function getProperty(): string
    {
        return 'productUpdateRequest';
    }
}
