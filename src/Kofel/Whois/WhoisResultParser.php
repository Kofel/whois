<?php
/**
 * Date: 17.05.2016
 * Time: 12:12
 */

namespace Kofel\Whois;


class WhoisResultParser
{
    /** @var array */
    private $tokens;

    /**
     * WhoisQueryParser constructor.
     * @param $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    public function parse($sld, $result): WhoisResult
    {
        $available = (false !== strpos($result, $this->tokens['available']));
        
        return new WhoisResult($sld, $result, $available);
    }
}