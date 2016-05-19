<?php
/**
 * Date: 17.05.2016
 * Time: 12:12
 */

namespace Kofel\Whois;


class WhoisQuery
{
    /** @var string */
    private $sld;

    /** @var string */
    private $query;

    /** @var bool */
    private $available;

    /**
     * WhoisQuery constructor.
     * @param string $sld
     * @param string $query
     * @param bool $available
     */
    public function __construct($sld, $query, $available)
    {
        $this->sld = $sld;
        $this->query = $query;
        $this->available = $available;
    }

    /**
     * @return string
     */
    public function getSld()
    {
        return $this->sld;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return boolean
     */
    public function isAvailable()
    {
        return $this->available;
    }
}