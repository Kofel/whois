<?php
/**
 * Date: 17.05.2016
 * Time: 11:23
 */

namespace Kofel\Whois;


use Kofel\Whois\Exception\InvalidJsonFileException;
use Kofel\Whois\Exception\UnsupportedDomainException;

class JsonWhoisServerFactory implements WhoisServerFactory
{
    const JSON_PATH = __DIR__ . '/Resources/whois.servers.json';

    /** @var string */
    private $path;

    /** @var array */
    private $serverList = [];

    /** @throws InvalidJsonFileException */
    public function __construct($path = self::JSON_PATH)
    {
        $this->path = $path;
        $this->loadServerList();
    }

    /**
     * @throws InvalidJsonFileException
     * @throws UnsupportedDomainException
     */
    public function getWhoisServer($sld): WhoisServer
    {
        $tld = $this->findTld($sld);
        $definition = $this->getTldDefinition($tld);

        return $this->createWhoisServer($definition);
    }

    /**
     * @throws InvalidJsonFileException
     * @throws UnsupportedDomainException
     */
    public function getResultParser($sld): WhoisResultParser
    {
        $tld = $this->findTld($sld);
        $definition = $this->getTldDefinition($tld);

        return new WhoisResultParser($definition['tokens']);
    }

    /** @throws UnsupportedDomainException */
    protected function findTld($sld): string
    {
        $chunks = explode('.', strtolower($sld));
        $count = count($chunks);
        
        for ($i = 0; $i < $count; $i++) {
            $tld = implode('.', array_slice($chunks, $i, $count));
            
            if (isset($this->serverList[$tld])) {
                return $tld;
            }
        }
        
        throw new UnsupportedDomainException(sprintf(
            '"%s" domain is not supported. Couldn\'t find Whois server.',
            $sld
        ));
    }

    /** @throws InvalidJsonFileException */
    protected function getTldDefinition($tld): array
    {
        if (
            !isset($this->serverList[$tld]) ||
            !is_array($this->serverList[$tld]) ||
            !array_key_exists('host', $this->serverList[$tld])
        ) {
            throw new InvalidJsonFileException(sprintf(
                'Invalid server definition for "%s" tld.',
                $tld
            ));
        }

        return $this->serverList[$tld];
    }

    /** @throws InvalidJsonFileException */
    protected function loadServerList()
    {
        $content = @file_get_contents($this->path);
        
        if (false === $content) {
            throw new InvalidJsonFileException(sprintf(
                'Couldn\'t read JSON file. "%s" is not readable.',
                $this->path
            ));
        }

        $content = @json_decode($content, true);

        if (null === $content || !is_array($content)) {
            throw new InvalidJsonFileException(sprintf(
                '"%s" is not valid JSON file.',
                $this->path
            ));
        }

        $this->serverList = $content;
    }

    /**
     * @param $tld string
     * @param $definition array
     * @return WhoisServer
     * @throws UnsupportedDomainException
     */
    protected function createWhoisServer($definition): WhoisServer
    {
        $host = $definition['host'];

        switch ($definition['protocol']) {
            case 'whois':
                return new WhoisServer($host);
            case 'proxy-whois':
                return new ProxyWhoisServer($host);
            case 'http':
                return new HttpWhoisServer($host);
        }
    }
}