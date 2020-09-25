<?php

declare(strict_type=1);

namespace Phpro\Scheduler\Model;

use Magento\Framework\DataObject;

class Job extends DataObject
{
    public const STATUS_DISABLED = 0;
    public const STATUS_ENABLED = 1;

    public function setStatus(int $status): self
    {
        $this->setData('status', $status);
        $this->setStatusName();

        return $this;
    }

    private function setStatusName(): void
    {
        if ($this->getStatus() === self::STATUS_ENABLED) {
            $this->setData('status_name', __('Enabled'));
            return;
        }

        $this->setData('status_name', __('Disabled'));
    }
}
