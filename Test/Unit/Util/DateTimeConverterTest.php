<?php

namespace Phpro\Scheduler\Test\Util;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ScopeResolverInterface;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\Timezone;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Phpro\Scheduler\Util\DateTimeConverter;
use PHPUnit\Framework\TestCase;

class DateTimeConverterTest extends TestCase
{
    /**
     * @var DateTimeConverter
     */
    private $converter;

    public function setUp()
    {
        /** @var TimezoneInterface $timezone */
        $scopeConfig = $this->createConfiguredMock(ScopeConfigInterface::class, [
            'getValue' => 'Europe/Brussels'
        ]);

        $timezone = new Timezone(
            $this->createMock(ScopeResolverInterface::class),
            $this->createMock(ResolverInterface::class),
            $this->createMock(DateTime::class),
            $scopeConfig,
            '',
            ''
        );

        $this->converter = new DateTimeConverter($timezone);
    }

    /**
     * @test
     */
    public function itCanConvertDateToTimestamp()
    {
        $date = '2019-05-31 12:46:07';
        $result = $this->converter->toTimestamp($date);

        $this->assertSame(1559331967, $result);
    }

    /**
     * @test
     */
    public function itCanConvertDateToCurrentHourTimestamp()
    {
        $date = '2019-05-31 14:48:47';
        $result = $this->converter->toHourTimestamp($date);

        $this->assertSame(1559318400, $result);
    }

    /**
     * @test
     */
    public function itCanConvertDateToNextHourTimestamp()
    {
        $date = '2019-05-31 14:48:47';
        $result = $this->converter->toNextHourTimestamp($date);

        $hourStart = '2019-05-31 14:00:00';
        $resultStart = $this->converter->toNextHourTimestamp($hourStart);

        $hourEnd = '2019-05-31 14:59:59';
        $resultEnd = $this->converter->toNextHourTimestamp($hourEnd);

        $this->assertSame(1559322000, $result);
        $this->assertSame(1559322000, $resultStart);
        $this->assertSame(1559322000, $resultEnd);
    }

    /**
     * @test
     */
    public function itCanConvertDatabaseValueToTimezoneDate()
    {
        $date = '2019-05-27 20:44:05';
        $result = $this->converter->convertDate($date);

        $this->assertSame('', $this->converter->convertDate(null));
        $this->assertSame('2019-05-27 22:44:05', $result);
    }
}
