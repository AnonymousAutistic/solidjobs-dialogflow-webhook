<?php
/**
 * Created by PhpStorm.
 * User: hans
 * Date: 16/09/19
 * Time: 9:53
 */

namespace Solidjobs\Intent\Services;

use Solidjobs\Intent\Service;


/**
 * Https service for make requests
 *
 * Class HttpsService
 * @package Solidjobs\Intent\Services
 */
class HttpsService extends Service
{

    /**
     * @param string $url
     * @param array $headers
     * @return mixed
     */
    public function get(string $url, array $headers)
    {
        /**
         * HTTP Request
         */
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // quickfix for localhost testing

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    /**
     * @param string $url
     * @param array $headers
     * @param string|array|object $data
     * @return mixed
     */
    public function put(string $url, array $headers, $data)
    {
        /**
         * Data is allowed to be sent as array or object, but it must be casted to json
         */
        $data = is_array($data) || is_object($data) ? json_encode($data) : $data;

        /**
         * Add content-length
         */
        $headers[] = 'Content-Length: ' . strlen($data);

        /**
         * HTTP Request
         */
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_PUT, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // quickfix for localhost testing

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    /**
     * @param string $url
     * @param array $headers
     * @param string|array|object $data
     * @return mixed
     */
    public function post(string $url, array $headers, $data)
    {
        /**
         * Data is allowed to be sent as array or object, but it must be casted to json
         */
        $data = is_array($data) || is_object($data) ? json_encode($data) : $data;

        /**
         * Add content-length
         */
        $headers[] = 'Content-Length: ' . strlen($data);

        /**
         * HTTP Request
         */
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // quickfix for localhost testing

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    /**
     * @param string $url
     * @param array $headers
     * @return mixed
     */
    public function delete(string $url, array $headers)
    {
        /**
         * HTTP Request
         */
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // quickfix for localhost testing

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

}
