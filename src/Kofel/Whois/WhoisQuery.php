<?php
/**
 * Date: 17.05.2016
 * Time: 12:12
 */

namespace Kofel\Whois;


class WhoisQuery
{
    /** @var string */
    private $query;

    /** @var bool */
    private $available;

    /**
     * WhoisQuery constructor.
     * @param string $query
     * @param bool $available
     */
    public function __construct($query, $available)
    {
        $this->query = $query;
        $this->available = $available;
    }
}