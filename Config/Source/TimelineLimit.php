<?php

declare(strict_types=1);

namespace Phpro\Scheduler\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class TimelineLimit implements OptionSourceInterface
{
    public const LIMIT_0 = 0;
    private const LIMIT_6 = 6;
    private const LIMIT_12 = 12;
    private const LIMIT_24 = 24;

    public function toOptionArray(): array
    {
        return [
            self::LIMIT_0 => __('Show all'),
            self::LIMIT_24 => __('Show past 24 hours'),
            self::LIMIT_12 => __('Show past 12 hours'),
            self::LIMIT_6 => __('Show past 6 hours'),
        ];
    }
}
