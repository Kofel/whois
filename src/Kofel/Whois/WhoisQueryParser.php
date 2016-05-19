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

    public function parse($query)
    {
        $available = (false !== strpos($query, $this->availableMatcher));
        
        return new WhoisQuery($query, $available);
    }
}