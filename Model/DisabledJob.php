<?php

namespace Phpro\Scheduler\Model;

use Magento\Framework\Model\AbstractModel;
use Phpro\Scheduler\Model\ResourceModel\DisabledJob as DisabledJobResource;

/**
 * @psalm-suppress DeprecatedClass
 */
class DisabledJob extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(DisabledJobResource::class);
    }
}
