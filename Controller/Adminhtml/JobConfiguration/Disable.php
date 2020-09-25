<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Controller\Adminhtml\JobConfiguration;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Phpro\Scheduler\Model\JobRepository;

class Disable extends Action
{
    const ADMIN_RESOURCE = 'Phpro_Scheduler::job_configuration';

    /**
     * @var JobRepository
     */
    private $jobRepository;

    public function __construct(
        Context $context,
        JobRepository $jobRepository
    ) {
        parent::__construct($context);
        $this->jobRepository = $jobRepository;
    }

    public function execute(): Redirect
    {
        $names = $this->getRequest()->getParam('names', []);

        foreach ($names as $jobName) {
            $this->jobRepository->disable($jobName);
        }

        $this->messageManager->addSuccessMessage(__('The selected jobs were disabled.'));

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/index');

        return $resultRedirect;
    }
}
