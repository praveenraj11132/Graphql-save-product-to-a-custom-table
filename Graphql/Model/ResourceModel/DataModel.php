<?php

namespace Wheelpros\Graphql\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class DataModel extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('recent', 'id');
    }
}
