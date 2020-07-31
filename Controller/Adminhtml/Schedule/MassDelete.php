<?php

namespace Phpro\Scheduler\Controller\Adminhtml\Schedule;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Ui\Component\MassAction\Filter;
use Phpro\Scheduler\Model\ResourceModel\Schedule\ScheduleCollectionFactory;

class MassDelete extends Action
{
    const ADMIN_RESOURCE = 'Phpro_Scheduler::schedule_delete';

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var ScheduleCollectionFactory
     */
    private $collection;

    public function __construct(Action\Context $context, Filter $filter, ScheduleCollectionFactory $collection)
    {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collection = $collection;
    }

    public function execute(): Redirect
    {
        if (!$this->getRequest()->isPost()) {
            throw new NotFoundException(__('Schedule not found.'));
        }

        $schedules = $this->filter->getCollection($this->collection->create());
        $size = $schedules->getSize();
        foreach ($schedules as $schedule) {
            $schedule->delete();
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $size));
        /** @var Redirect $redirect */
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $redirect->setPath('*/*/');
    }
}
