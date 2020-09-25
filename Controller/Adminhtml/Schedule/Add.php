<?php
namespace Phpro\Scheduler\Controller\Adminhtml\Schedule;

use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\Page;

class Add extends BackendAction
{
    const ADMIN_RESOURCE = 'Phpro_Scheduler::schedule_save';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $resultPage->setActiveMenu('Phpro_Scheduler::schedule');
        $resultPage->addBreadcrumb(__('Schedule Job'), __('Schedule Job'));
        $resultPage->getConfig()->getTitle()->prepend(__('Schedule Job'));

        return $resultPage;
    }
}
