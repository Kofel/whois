<?php
/**
 * Date: 10.06.2016
 * Time: 09:47
 */

namespace Kofel\Whois;


class WhoisQuery
{
    /** @var WhoisServerFactory */
    private $serverFactory;

    /** @var int */
    private $timeout = 2;

    /**
     * WhoisQuery constructor.
     * @param WhoisServerFactory $serverFactory
     */
    public function __construct(WhoisServerFactory $serverFactory)
    {
        $this->serverFactory = $serverFactory;
    }

    public function query($sld): WhoisResult
    {
        $server = $this->serverFactory->getWhoisServer($sld);
        $resultParser = $this->serverFactory->getResultParser($sld);
        $result = $server->query($sld, $this->timeout);
        return $resultParser->parse($sld, $result);
    }

    public function getServerFactory(): WhoisServerFactory
    {
        return $this->serverFactory;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }
}