<?php
/**
 * Date: 10.06.2016
 * Time: 10:01
 */

namespace Kofel\Whois;


class ProxyWhoisServer extends WhoisServer
{
    public function query($sld, $timeout = 2): string
    {
        $serversQuery = $this->queryHost($this->host, '=' . $sld);

        $match = [];
        if (!preg_match('/Whois Server: ([^\\s]+)/', $serversQuery, $match)) {
            return $serversQuery;
        }
        $host = $match[1];

        return $this->queryHost($host, $sld, $timeout);
    }
}