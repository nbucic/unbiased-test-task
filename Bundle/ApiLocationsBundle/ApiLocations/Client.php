<?php
/**
 * Created by PhpStorm.
 * User: nbucic
 * Date: 24.02.15.
 * Time: 14:50
 */

namespace Nbucic\Bundle\ApiLocationsBundle\ApiLocations;

use Nbucic\Bundle\ApiLocationsBundle\ApiLocations\Exception\ConnectionException;
use Nbucic\Bundle\ApiLocationsBundle\ApiLocations\Exception\InvalidResponseException;

class Client
{

    protected $url;

    /**
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     * @throws ConnectionException
     * @throws InvalidResponseException
     */
    public function getLocations()
    {
        $response = $this->getCurlRequest(sprintf("%s/%s", $this->getUrl(), "locations"));
        $response = json_decode($response, true);

        if ($response === false) {
            throw new ConnectionException('Invalid response from server') ;
        }

        $this->validateResponse($response);

        return $response['data'];
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $response
     * @throws InvalidResponseException
     */
    protected function validateResponse($response)
    {
        if (!is_array($response)) {
            throw new InvalidResponseException('Server returned non valid data');
        }

        if (!isset($response['success'])) {
            throw new InvalidResponseException('Server returned data without success response');
        }

        if (!isset($response['data'])) {
            throw new InvalidResponseException('Server returned empty payload');
        }

        if ((bool) $response['success'] === false) {
            if (!isset($response['data']['message'])) {
                throw new InvalidResponseException('Server didn\'t return error message');
            }

            $message = $response['data']['message'];
            $code = null;

            if (isset($response['data']['code'])) {
                $code = $response['data']['code'];
            }

            throw new InvalidResponseException($message, $code);
        }
    }

    /**
     * @param $url
     * @return mixed
     * @throws ConnectionException
     */
    protected function getCurlRequest($url)
    {
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
            ]
        );

        $curlResponse = curl_exec($ch);

        if (curl_errno($ch) > 0) {
            throw new ConnectionException(sprintf("Error: %s", curl_error($ch)));
        }

        return $curlResponse;
    }

}