<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Controller\Adminhtml\Schedule;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Phrase;
use Magento\Ui\Component\MassAction\Filter;
use Phpro\Scheduler\Factory\Schedule\ScheduleCollectionFactory;

/**
 * @psalm-suppress UndefinedInterfaceMethod
 */
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
    private $collectionFactory;

    public function __construct(Action\Context $context, Filter $filter, ScheduleCollectionFactory $collectionFactory)
    {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
    }

    public function execute(): Redirect
    {
        /** @psalm-suppress UndefinedInterfaceMethod */
        if (!$this->getRequest()->isPost()) {
            throw new NotFoundException(new Phrase(__('Schedule not found.')));
        }

        $schedules = $this->filter->getCollection($this->collectionFactory->create());
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
