<?php
/**
 * Date: 17.05.2016
 * Time: 12:12
 */

namespace Kofel\Whois;


class WhoisQueryParser
{
    private $availableMatcher;

    /**
     * WhoisQueryParser constructor.
     * @param $availableMatcher
     */
    public function __construct($availableMatcher)
    {
        $this->availableMatcher = $availableMatcher;
    }

    /** @return WhoisQuery */
    public function parse($sld, $query)
    {
        $available = (false !== strpos($query, $this->availableMatcher));
        
        return new WhoisQuery($sld, $query, $available);
    }
}