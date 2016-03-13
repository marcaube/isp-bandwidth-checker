<?php

namespace Ob\Bandwidth;

final class BandwidthUsage
{
    /**
     * @var \DateTimeInterface
     */
    private $periodStart;

    /**
     * @var \DateTimeInterface
     */
    private $periodEnd;

    /**
     * @var float
     */
    private $allottedBandwidth;

    /**
     * @var float
     */
    private $usedBandwidth;

    /**
     * @param \DateTimeInterface $periodStart       Start of the billing period
     * @param \DateTimeInterface $periodEnd         End of the billing period
     * @param float              $allottedBandwidth Allotted bandwidth in gigabytes
     * @param float              $usedBandwidth     Used bandwidth in gigabytes
     */
    public function __construct(
        \DateTimeInterface $periodStart,
        \DateTimeInterface $periodEnd,
        $allottedBandwidth,
        $usedBandwidth
    ) {
        $this->periodStart       = $periodStart;
        $this->periodEnd         = $periodEnd;
        $this->allottedBandwidth = $allottedBandwidth;
        $this->usedBandwidth     = $usedBandwidth;
    }

    /**
     * @return \DateTimeInterface
     */
    public function periodStart()
    {
        return $this->periodStart;
    }

    /**
     * @return \DateTimeInterface
     */
    public function periodEnd()
    {
        return $this->periodEnd;
    }

    /**
     * @return float
     */
    public function allottedBandwidth()
    {
        return $this->allottedBandwidth;
    }

    /**
     * @return float
     */
    public function usedBandwidth()
    {
        return $this->usedBandwidth;
    }

    /**
     * @return float
     */
    public function usageRatio()
    {
        return number_format($this->usedBandwidth() / $this->allottedBandwidth() * 100, 2);
    }
}
