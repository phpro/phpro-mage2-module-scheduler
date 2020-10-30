<?php

namespace Phpro\Scheduler\Block\Adminhtml\JobConfiguration;

use Magento\Backend\Block\Widget\Grid\Massaction as GridMassAction;
use Magento\Framework\Data\Collection;

/**
 * @method string getMassactionIdField()
 */
class Massaction extends GridMassAction
{
    /**
     * BUGFIX https://github.com/magento/magento2/issues/9610
     */
    public function getGridIdsJson(): string
    {
        $parent = $this->getParentBlock();

        if (is_bool($parent) || !$this->getUseSelectAll()) {
            return '';
        }

        /** @var Collection $allIdsCollection */
        $allIdsCollection = clone $parent->getCollection();

        if ($this->getMassactionIdField()) {
            $massActionIdField = $this->getMassactionIdField();
        } else {
            $massActionIdField = $parent->getMassactionIdField();
        }

        $gridIds = $allIdsCollection->setPageSize(0)->getColumnValues($massActionIdField);
        if (!empty($gridIds)) {
            return implode(",", $gridIds);
        }
        return '';
    }
}
