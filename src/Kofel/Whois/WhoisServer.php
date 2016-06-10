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

    /** @var string */
    protected $host;

    /**
     * WhoisServer constructor.
     * @param $host
     */
    public function __construct($host)
    {
        $this->host = $host;
    }

    /** @throws WhoisServerConnectionException */
    public function query($sld, $timeout = 2): string
    {
        return $this->queryHost($this->host, $sld, $timeout);
    }

    /** @throws WhoisServerConnectionException */
    protected function queryHost($host, $sld, $timeout = 2): string
    {
        $socket = @fsockopen($host, self::WHOIS_PORT, $errno, $errstr, $timeout);
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
            $buffer .= fread($socket, 128);
        }

        $info = stream_get_meta_data($socket);

        if ($info['timed_out']) {
            throw new WhoisServerConnectionException(sprintf(
                'Connection to "%s" timed out.',
                $this->host
            ));
        }

        fclose($socket);

        $encoding = mb_detect_encoding($buffer, "UTF-8, ISO-8859-1, ISO-8859-15", true);
        $buffer = mb_convert_encoding($buffer, "UTF-8", $encoding);
        $buffer = htmlspecialchars($buffer, ENT_COMPAT, "UTF-8", true);

        return $buffer;
    }
}