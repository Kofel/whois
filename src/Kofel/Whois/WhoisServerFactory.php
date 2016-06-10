<?php
/**
 * Date: 10.06.2016
 * Time: 09:48
 */
namespace Kofel\Whois;

use Kofel\Whois\Exception\InvalidJsonFileException;
use Kofel\Whois\Exception\UnsupportedDomainException;

interface WhoisServerFactory
{
    /**
     * @throws InvalidJsonFileException
     * @throws UnsupportedDomainException
     */
    public function getWhoisServer($sld): WhoisServer;

    /**
     * @throws InvalidJsonFileException
     * @throws UnsupportedDomainException
     */
    public function getResultParser($sld): WhoisResultParser;
}