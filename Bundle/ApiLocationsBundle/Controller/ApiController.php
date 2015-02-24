<?php

namespace Nbucic\Bundle\ApiLocationsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Parser;

class ApiController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Template
     */
    public function getJsonLocationsAction()
    {
        $parser = new Parser();
        $demoLocationsFile = sprintf(
            "%s/../Resources/%s",
            __DIR__,
            $this->get('service_container')->getParameter("demo_locations_file")
        );
        $demoLocations = $parser->parse(file_get_contents($demoLocationsFile));

        return
            [
                'data' => [
                    'data' => $demoLocations,
                    'success' => true,
                ]
            ];
    }
}
