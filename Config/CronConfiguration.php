<?php
declare(strict_types = 1);

namespace Phpro\Scheduler\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Phpro\Scheduler\Config\Source\TimelineLimit;

class CronConfiguration
{
    private const XML_RUNNING_LIFETIME = 'system/cron/cron_general/running_job_lifetime';
    private const XML_TIMELINE_LIMIT = 'system/cron/cron_general/timeline_view_limit';
    private const XML_LIMIT_SUCCESSFUL = 'system/cron/cron_general/limit_successful_jobs';

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    public function __construct(ScopeConfigInterface $config)
    {
        $this->config = $config;
    }

    public function getRunningLifetime(): string
    {
        return $this->config->getValue(self::XML_RUNNING_LIFETIME);
    }

    public function getTimelineLimit(): string
    {
        $limit = $this->config->getValue(self::XML_TIMELINE_LIMIT);

        return $limit ?: TimelineLimit::LIMIT_0;
    }

    public function limitSuccessfulJobs(): bool
    {
        return (bool) $this->config->getValue(self::XML_LIMIT_SUCCESSFUL);
    }
}
