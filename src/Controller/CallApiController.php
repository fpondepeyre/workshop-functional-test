<?php

namespace App\Controller;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CallApiController extends AbstractController
{
    /**
     * @Route("/call_api", name="app_call_api")
     */
    public function index(ClientInterface $clientHttp): Response
    {
        $response = $clientHttp->sendRequest(new Request('GET', 'https://hub.dummyapis.com/products?noofRecords=10&i&currency=eur'));

        $products = json_decode($response->getBody()->getContents(), true);

        return $this->render('call_api/index.html.twig', [
            'products' => $products,
        ]);
    }
}
