<?php

namespace Phpro\Scheduler\Block\Adminhtml\JobConfiguration;

use Magento\Backend\Block\Widget\Grid\Massaction as GridMassAction;

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
        if (!$this->getUseSelectAll() || !$parent = $this->getParentBlock()) {
            return '';
        }

        /** @var \Magento\Framework\Data\Collection $allIdsCollection */
        $allIdsCollection = clone $parent->getCollection();

        if ($this->getMassactionIdField()) {
            $massActionIdField = $this->getMassactionIdField();
        } else {
            $massActionIdField = $parent->getMassactionIdField();
        }

        $gridIds = $allIdsCollection->setPageSize(0)->getColumnValues($massActionIdField);
        if (!empty($gridIds)) {
            return join(",", $gridIds);
        }
        return '';
    }
}
