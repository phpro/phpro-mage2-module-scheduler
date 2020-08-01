<?php
declare(strict_types=1);

namespace Phpro\Scheduler\Service;

use Magento\Cron\Model\Schedule;
use Phpro\Scheduler\Api\Service\ProviderInterface;
use Phpro\Scheduler\Util\DateTimeConverter;
use Phpro\Scheduler\Data\Schedule as ScheduleData;

class CronDataService
{
    /**
     * @var ProviderInterface[]
     */
    private $providers;

    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    public function getCronData(): array
    {
        $data = [];

        foreach ($this->providers as $provider) {
            $data = array_merge($data, $provider->provide());
        }

        return $data;
    }
}
