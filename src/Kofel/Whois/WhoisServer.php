<?php
/**
 * Date: 16.05.2016
 * Time: 16:48
 */

namespace Kofel\Whois;


use Kofel\Whois\Exception\WhoisServerConnectionException;

class WhoisServer
{
    const WHOIS_PORT = 43;

    private $host;

    private $tld;

    /**
     * WhoisServer constructor.
     * @param $host
     * @param $tld
     */
    public function __construct($host, $tld)
    {
        $this->host = $host;
        $this->tld = $tld;
    }

    /** @throws WhoisServerConnectionException @return string */
    public function query($sld)
    {
        if (in_array($this->tld, ['com', 'net'])) {
            $serversQuery = $this->queryHost($this->host, '=' . $sld);

            $match = [];
            if (!preg_match('/Whois Server: ([^\\s]+)/', $serversQuery, $match)) {
                return $serversQuery;
            }

            $host = $match[1];
            return $this->queryHost($host, $sld);
        }
        else {
            return $this->queryHost($this->host, $sld);
        }
    }

    protected function queryHost($host, $sld)
    {
        $socket = fsockopen($host, self::WHOIS_PORT);
        if (false === $socket) {
            throw new WhoisServerConnectionException(sprintf(
                'Cannot establish connection to "%s".',
                $this->host
            ));
        }

        fwrite($socket, $sld);
        fwrite($socket, "\r\n");

        $buffer = '';
        while(!feof($socket)) {
            $buffer .= fgets($socket, 128);
        }

        fclose($socket);

        $encoding = mb_detect_encoding($buffer, "UTF-8, ISO-8859-1, ISO-8859-15", true);
        $buffer = mb_convert_encoding($buffer, "UTF-8", $encoding);
        $buffer = htmlspecialchars($buffer, ENT_COMPAT, "UTF-8", true);

        return $buffer;
    }
}