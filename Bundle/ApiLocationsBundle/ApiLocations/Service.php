<?php
/**
 * Created by PhpStorm.
 * User: nbucic
 * Date: 24.02.15.
 * Time: 15:24
 */

namespace Nbucic\Bundle\ApiLocationsBundle\ApiLocations;


use Nbucic\Bundle\ApiLocationsBundle\ApiLocations\Exception\InvalidResponseException;
use Symfony\Component\Translation\Exception\InvalidResourceException;

class Service {

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getLocations()
    {
        $response = $this->client->getLocations();
        $result = [];

        if (!isset($response['locations'])) {
            throw new InvalidResponseException('Response does not contain valid locations');
        }

        if (!is_array($response['locations'])) {
            throw new InvalidResourceException('Response does not contain valid locations');
        }

        foreach ($response['locations'] as $location) {
            array_push($result, new Location($location));
        }

        return $result;

    }

}