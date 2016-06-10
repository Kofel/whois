<?php
/**
 * Date: 17.05.2016
 * Time: 12:12
 */

namespace Kofel\Whois;


class WhoisResult
{
    /** @var string */
    private $sld;

    /** @var string */
    private $info;

    /** @var bool */
    private $available;

    /**
     * WhoisResult constructor.
     * @param string $sld
     * @param string $info
     * @param bool $available
     */
    public function __construct(string $sld, string $info, bool $available)
    {
        $this->sld = $sld;
        $this->info = $info;
        $this->available = $available;
    }

    public function getSld(): string
    {
        return $this->sld;
    }

    public function getInfo(): string
    {
        return $this->info;
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }
}