<?php

namespace App\Response;

class ProductList
{
    /**
     * @var ProductInfo[]
     */
    private ?array $list;

    /**
     * @return array|null
     */
    public function getList(): ?array
    {
        return $this->list;
    }

    /**
     * @param array|null $list
     * @return ProductList
     */
    public function setList(?array $list): ProductList
    {
        $this->list = $list;
        return $this;
    }

    public static function getInstance($list): self
    {
        $responseList = [];
        foreach ($list as $item) {
            $responseList[] = ProductInfo::getInstance($item);
        }

        $item = new self();
        return $item->setList($responseList)
        ;
    }
}