<?php

declare(strict_types = 1);

namespace Phpro\Scheduler\Util;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class DateTimeConverter
{
    /**
     * @var TimezoneInterface
     */
    private $timezone;

    public function __construct(TimezoneInterface $timezone)
    {
        $this->timezone = $timezone;
    }

    public function toTimestamp(string $date): int
    {
        return $this->timezone->date(new \DateTime($date))->getTimestamp();
    }

    public function toHourTimestamp(string $date): int
    {
        $convertedDate = $this->timezone->date(new \DateTime($date))->format('Y-m-d H:00:00');

        return $this->timezone->date(new \DateTime($convertedDate))->getTimestamp();
    }

    public function toNextHourTimestamp(string $date): int
    {
        $convertedDate = $this->timezone->date(new \DateTime($date))->modify('+1 hour')->format('Y-m-d H:00:00');

        return $this->timezone->date(new \DateTime($convertedDate))->getTimestamp();
    }

    public function toCurrentTimestamp(): int
    {
        return $this->timezone->scopeTimeStamp();
    }

    public function convertDate(?string $date): string
    {
        if ($date === null) {
            return '';
        }

        return $this->timezone->date(new \DateTime($date))->format('Y-m-d H:i:s');
    }
}
