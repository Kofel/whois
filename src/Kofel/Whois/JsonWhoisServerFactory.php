<?php
/**
 * Date: 17.05.2016
 * Time: 11:23
 */

namespace Kofel\Whois;


use Kofel\Whois\Exception\InvalidJsonFileException;
use Kofel\Whois\Exception\UnsupportedDomainException;

class JsonWhoisServerFactory
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

    /** @throws InvalidJsonFileException @throws UnsupportedDomainException */
    public function getWhoisServer($sld)
    {
        $tld = $this->findTld($sld);
        $definition = $this->getTldDefinition($tld);

        $host = $definition[0];
        if (preg_match('/^https?:\/\//i', $host)) {
            throw new UnsupportedDomainException(sprintf(
                'https whois server is currently unsupported.'
            ));
        }

        return new WhoisServer($host, $tld);
    }

    /** @return WhoisQueryParser */
    public function getQueryParser($sld)
    {
        $tld = $this->findTld($sld);
        $definition = $this->getTldDefinition($tld);

        $host = $definition[0];

        return new WhoisQueryParser($definition[1]);
    }

    /** @throws UnsupportedDomainException @return string */
    protected function findTld($sld)
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
    protected function getTldDefinition($tld)
    {
        if (
            !isset($this->serverList[$tld]) ||
            !is_array($this->serverList[$tld]) ||
            2 !== count($this->serverList[$tld])
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
}