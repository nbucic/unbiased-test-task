<?php
/**
 * Created by PhpStorm.
 * User: nbucic
 * Date: 24.02.15.
 * Time: 15:18
 */

namespace Nbucic\Bundle\ApiLocationsBundle\ApiLocations;


use Nbucic\Bundle\ApiLocationsBundle\ApiLocations\Exception\InvalidResponseException;
use Symfony\Component\Translation\Exception\InvalidResourceException;

class Location
{

    protected $name;
    protected $lat;
    protected $lng;

    /**
     * @param $location
     * @throws InvalidResponseException
     */
    public function __construct($location)
    {
        $this->validate($location);

        $this
            ->setName($location['name'])
            ->setLat($location['coordinates']['lat'])
            ->setLng($location['coordinates']['lng']);
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param mixed $lat
     * @return $this
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * @param mixed $lng
     * @return $this
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * @param $location
     * @throws InvalidResponseException
     */
    private function validate($location)
    {
        if (!is_array($location)) {
            throw new InvalidResponseException("Response must be array");
        }

        if (!isset($location['name'])) {
            throw new InvalidResponseException("Response must include name of location");
        }

        $coordinates = $location['coordinates'];

        if (!isset($coordinates) || !isset($coordinates['lat']) || !isset($coordinates['lng'])) {
            throw new InvalidResourceException("Response does not contain valid location");
        }

        unset($coordinates);
    }
}