<?php
/**
 * Created by PhpStorm.
 * User: nbucic
 * Date: 24.02.15.
 * Time: 19:46
 */

namespace Nbucic\Bundle\ApiLocationsBundle\Tests\ApiLocations;


use Nbucic\Bundle\ApiLocationsBundle\ApiLocations\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientTest extends WebTestCase
{

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    private $client;

    public function testSuccessResponse()
    {
        $result = ['Paris', 'Zagreb', 'Wien'];
        $this->client->expects($this->once())
            ->method('getCurlRequest')
            ->will(
                $this->returnValue(
                    json_encode(
                        [
                            'data' => $result,
                            'success' => true,
                        ]
                    )
                )
            );

        $response = $this->client->getLocations();

        $this->assertEquals($result, $response);
    }

    /**
     * @expectedException Nbucic\Bundle\ApiLocationsBundle\ApiLocations\Exception\InvalidResponseException
     * @expectedExceptionMessage Server returned non valid data
     */
    public function testNoArrayData()
    {
        $this->client->expects($this->once())
            ->method('getCurlRequest')
            ->will(
                $this->returnValue(
                    ''
                )
            );

        $this->client->getLocations();
    }

    /**
     * @expectedException Nbucic\Bundle\ApiLocationsBundle\ApiLocations\Exception\InvalidResponseException
     * @expectedExceptionMessage Server returned data without success response
     */
    public function testNoSuccessResponse()
    {
        $this->client->expects($this->once())
            ->method('getCurlRequest')
            ->will(
                $this->returnValue(
                    json_encode(
                        [
                            'data' => 'testString',
                        ]
                    )
                )
            );

        $this->client->getLocations();

    }

    /**
     * @expectedException Nbucic\Bundle\ApiLocationsBundle\ApiLocations\Exception\InvalidResponseException
     * @expectedExceptionMessage Server returned empty payload
     */
    public function testNoDataResponse()
    {
        $this->client->expects($this->once())
            ->method('getCurlRequest')
            ->will(
                $this->returnValue(
                    json_encode(
                        [
                            'success' => 'true',
                        ]
                    )
                )
            );

        $this->client->getLocations();
    }

    /**
     * @expectedException \Nbucic\Bundle\ApiLocationsBundle\ApiLocations\Exception\ConnectionException
     */
    public function testWrongAddress()
    {
        $client = new Client('demo.url');
        $client->getLocations();

    }

    protected function setUp()
    {
        $this->client = $this->getMockBuilder('Nbucic\Bundle\ApiLocationsBundle\ApiLocations\Client')
            ->setMethods(['getCurlRequest'])
            ->disableOriginalConstructor()
            ->getMock();
    }


}