<?php

namespace Ob\Bandwidth;

interface InternetServiceProvider
{
    /**
     * Get your bandwidth usage statistics from the ISP.
     *
     * @return BandwidthUsage
     *
     * @throws InvalidCredentials
     */
    public function getBandwidthUsage() : BandwidthUsage;
}
