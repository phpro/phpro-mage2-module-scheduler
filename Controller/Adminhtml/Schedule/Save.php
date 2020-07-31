<?php
namespace Phpro\Scheduler\Controller\Adminhtml\Schedule;

use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\Model\View\Result\Redirect;
use Phpro\Scheduler\Model\ScheduleManager;
use Magento\Backend\App\Action\Context;

/**
 * Class Save
 * @package Phpro\Scheduler\Controller\Adminhtml\Schedule
 */
class Save extends BackendAction
{
    const ADMIN_RESOURCE = 'Phpro_Scheduler::schedule_save';

    /**
     * @var ScheduleManager
     */
    private $scheduler;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param ScheduleManager $scheduler
     */
    public function __construct(Context $context, ScheduleManager $scheduler)
    {
        parent::__construct($context);
        $this->scheduler = $scheduler;
    }

    /**
     * Save action
     *
     * @return Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/index');

        if (!isset($data['job_code'])) {
            return $resultRedirect;
        }

        $jobCode = $data['job_code'];

        try {
            $this->scheduler->addJobToSchedule($jobCode);
            $this->messageManager->addSuccessMessage("Added $jobCode to schedule");
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage("Could not add $jobCode to schedule");
        }

        return $resultRedirect;
    }
}
