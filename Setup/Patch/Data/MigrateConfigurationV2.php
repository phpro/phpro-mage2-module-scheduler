<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Phpro\Scheduler\Config\CronConfiguration;

class MigrateConfigurationV2 implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [MigrateConfiguration::class];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function apply(): void
    {
        $this->moduleDataSetup->startSetup();

        $this->moduleDataSetup->getConnection()->update(
            $this->moduleDataSetup->getTable('core_config_data'),
            ['path' => CronConfiguration::XML_RUNNING_LIFETIME],
            'path="system/cron/cron_general/running_job_lifetime"'
        );

        $this->moduleDataSetup->getConnection()->update(
            $this->moduleDataSetup->getTable('core_config_data'),
            ['path' => CronConfiguration::XML_AUTO_CRON_CHECK],
            'path="system/cron/cron_general/auto_cron_check"'
        );

        $this->moduleDataSetup->getConnection()->update(
            $this->moduleDataSetup->getTable('core_config_data'),
            ['path' => CronConfiguration::XML_LIMIT_SUCCESSFUL],
            'path="system/cron/cron_general/limit_successful_jobs"'
        );

        $this->moduleDataSetup->getConnection()->update(
            $this->moduleDataSetup->getTable('core_config_data'),
            ['path' => CronConfiguration::XML_TIMELINE_LIMIT],
            'path="system/cron/cron_general/timeline_view_limit"'
        );

        $this->moduleDataSetup->endSetup();
    }
}
