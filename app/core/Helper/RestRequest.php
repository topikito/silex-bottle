<?php

namespace Topikito\Acme\Helper;

/**
 * Class RestRequest
 *
 * @package Topikito\Acme\Helper
 */
class RestRequest
{

    /**
     * @param $parameters
     *
     * @return string
     */
    static protected function _prepareGetParams($parameters)
    {
        $getValues = [];
        foreach ($parameters as $key => $value)
        {
            if (!is_null($value))
            {
                $getValues[] = $key . '=' . urlencode($value);
            }
        }

        $getString = '';
        if (!empty($getValues))
        {
            $getString = implode('&', $getValues);
        }

        return $getString;
    }

    /**
     * @param        $url
     * @param string $method
     * @param array  $params
     * @param array  $headers
     *
     * @return mixed
     */
    static function call($url, $method = 'GET', $params = [], $headers = [])
    {
        $ch = curl_init();

        if (!empty($params))
        {
            switch ($method)
            {
                case 'POST':
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                    break;

                default:
                case 'GET':
                    $getPrefix = '?';
                    if (strstr($url, '?'))
                    {
                        $getPrefix = '&';
                    }
                    $url .= $getPrefix . self::_prepareGetParams($params);
                    break;
            }
        }

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $rawHeader = substr($response, 0, $header_size);
        $header = explode("\n", $rawHeader);
        $processedHeader = [];
        foreach ($header as &$subHeader) {
            $firstPos = strpos($subHeader, ':');
            if ($firstPos !== false) {
                $headerName = trim(substr($subHeader, 0, $firstPos));
                $headerValue = trim(substr($subHeader, $firstPos + 1));

                $processedHeader[$headerName] = $headerValue;
            }
        }
        $body = substr($response, $header_size);

        curl_close($ch);

        return ['header' => $processedHeader, 'header_raw' => $rawHeader, 'body' => $body];
    }

}
