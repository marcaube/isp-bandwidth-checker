<?php

use Ob\Bandwidth\BandwidthUsage;

class BandwidthUsageTest extends \PHPUnit_Framework_TestCase
{
    public function testCanBeCreated()
    {
        $usage = new BandwidthUsage(
            new \DateTimeImmutable('2016-03-03'),
            new \DateTimeImmutable('2016-04-03'),
            130,
            24.4
        );

        $this->assertInstanceOf(BandwidthUsage::class, $usage);

        return $usage;
    }

    /**
     * @depends testCanBeCreated
     */
    public function testBillingPeriodStartDateCanBeRetrieved(BandwidthUsage $usage)
    {
        $this->assertEquals('2016-03-03', $usage->periodStart()->format('Y-m-d'));
    }

    /**
     * @depends testCanBeCreated
     */
    public function testBillingPeriodEndDateCanBeRetrieved(BandwidthUsage $usage)
    {
        $this->assertEquals('2016-04-03', $usage->periodEnd()->format('Y-m-d'));
    }

    /**
     * @depends testCanBeCreated
     */
    public function testBandwidthAllotmentCanBeRetrieved(BandwidthUsage $usage)
    {
        $this->assertEquals(130, $usage->allottedBandwidth());
    }

    /**
     * @depends testCanBeCreated
     */
    public function testBandwidthUsageCanBeRetrieved(BandwidthUsage $usage)
    {
        $this->assertEquals(24.4, $usage->usedBandwidth());
    }

    /**
     * @depends testCanBeCreated
     */
    public function testBandwidthUsageRatioCanBeRetrieved(BandwidthUsage $usage)
    {
        // One does not simply compare two floating point numbers in PHP...
        $delta    = 0.001;
        $expected = 18.77;

        $this->assertTrue(abs(($usage->usageRatio() - $expected) / $expected) < $delta);
    }
}
