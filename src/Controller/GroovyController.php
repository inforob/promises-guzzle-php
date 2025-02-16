<?php

namespace App\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

final class GroovyController extends AbstractController
{
    #[Route('/api/sync', name: 'api_sync')]
    public function syncCall(): JsonResponse
    {
        $startTime = microtime(true);

        $client = new Client();

        // Llamadas síncronas a APIs externas
        $response1 = $client->get('https://dummyjson.com/posts/?delay=1000');

        $response2 = $client->get('https://dummyjson.com/posts/?delay=1000');

        $response3 = $client->get('https://dummyjson.com/posts/?delay=1000');

        $response3 = $client->get('https://dummyjson.com/posts/?delay=1000');

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);

        return new JsonResponse([
            'execution_time' => round($executionTime, 2) . ' segundos'
        ]);
    }


    /**
     * @throws Throwable
     */
    #[Route('/api/async', name: 'api_async')]
    public function asyncCall(): JsonResponse
    {
        $startTime = microtime(true);

        $client = new Client();

        // Crear las promises para cada llamada API
        $promises = [
            'api1' => $client->getAsync('https://dummyjson.com/posts/?delay=1000'),
            'api2' => $client->getAsync('https://dummyjson.com/posts/?delay=1000'),
            'api3' => $client->getAsync('https://dummyjson.com/posts/?delay=1000'),
            'api4' => $client->getAsync('https://dummyjson.com/posts/?delay=1000'),
        ];

        // Esperar a que todas las promises se resuelvan
        $results = Utils::unwrap($promises);

        // Procesar los resultados
        $data = array_map(function ($response) {
            return json_decode($response->getBody(), true);
        }, $results);

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);

        return new JsonResponse([
            'execution_time' => round($executionTime, 2) . ' segundos'
        ]);
    }

}
