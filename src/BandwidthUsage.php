<?php

namespace Ob\Bandwidth;

final class BandwidthUsage
{
    /**
     * @var \DateTimeImmutable
     */
    private $periodStart;

    /**
     * @var \DateTimeImmutable
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
        float $allottedBandwidth,
        float $usedBandwidth
    ) {
        $this->periodStart       = new \DateTimeImmutable($periodStart->format('Y-m-d'));
        $this->periodEnd         = new \DateTimeImmutable($periodEnd->format('Y-m-d'));
        $this->allottedBandwidth = $allottedBandwidth;
        $this->usedBandwidth     = $usedBandwidth;
    }

    public function periodStart() : \DateTimeImmutable
    {
        return $this->periodStart;
    }

    public function periodEnd() : \DateTimeImmutable
    {
        return $this->periodEnd;
    }

    public function allottedBandwidth() : float
    {
        return $this->allottedBandwidth;
    }

    public function usedBandwidth() : float
    {
        return $this->usedBandwidth;
    }

    public function usageRatio() : float
    {
        return number_format($this->usedBandwidth() / $this->allottedBandwidth() * 100, 2);
    }
}
