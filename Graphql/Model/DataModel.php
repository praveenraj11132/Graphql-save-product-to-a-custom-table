<?php

namespace Wheelpros\Graphql\Model;

use Magento\Framework\Model\AbstractModel;
use Wheelpros\Graphql\Model\ResourceModel\DataModel as DataModelResource;

class DataModel extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(DataModelResource::class);
    }

    public static function create()
    {
        return \Magento\Framework\App\ObjectManager::getInstance()->create(self::class);
    }

// Add your custom methods and attributes as needed
}
