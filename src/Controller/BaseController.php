<?php


namespace App\Controller;


use App\Utils\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param $data
     * @param array $groups An array of groups used to select the returned fields.
     * @return Response Return a Response object with json data.
     */
    protected function createJsonResponse($data, array $groups = []): Response
    {
        return new Response($this->serializer->serialize($data, 'json', $groups ? ['groups' => $groups] : []));
    }

}
