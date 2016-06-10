<?php
/**
 * Date: 10.06.2016
 * Time: 12:30
 */

namespace Kofel\Whois;


use Kofel\Whois\Exception\WhoisServerConnectionException;

class HttpWhoisServer extends WhoisServer
{
    public function query($sld, $timeout = 2): string
    {
        $url = sprintf('%s%s', $this->host, $sld);

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($handle, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
        $responseBody = curl_exec($handle);

        if (($error = curl_error($handle))) {
            throw new WhoisServerConnectionException(sprintf(
                'Failed to query "%s": %s',
                $url,
                $error
            ));
        }
        curl_close($handle);

        return strip_tags($responseBody);
    }
}