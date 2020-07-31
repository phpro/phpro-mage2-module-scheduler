<?php

namespace Phpro\Scheduler\Block\Adminhtml\JobConfiguration;

/**
 * Class Massaction
 * @package Phpro\Scheduler\Block\Adminhtml\JobConfiguration
 */
class Massaction extends \Magento\Backend\Block\Widget\Grid\Massaction
{

    /**
     * @return string
     * BUGFIX https://github.com/magento/magento2/issues/9610
     */
    public function getGridIdsJson()
    {
        if (!$this->getUseSelectAll()) {
            return '';
        }
        /** @var \Magento\Framework\Data\Collection $allIdsCollection */
        $allIdsCollection = clone $this->getParentBlock()->getCollection();

        if ($this->getMassactionIdField()) {
            $massActionIdField = $this->getMassactionIdField();
        } else {
            $massActionIdField = $this->getParentBlock()->getMassactionIdField();
        }

        $gridIds = $allIdsCollection->setPageSize(0)->getColumnValues($massActionIdField);
        if (!empty($gridIds)) {
            return join(",", $gridIds);
        }
        return '';
    }
}
