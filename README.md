## Links
https://docs.guzzlephp.org/en/stable/overview.html
https://symfony.com/doc/current/http_client.html#concurrent-requests

## Github repository
https://github.com/inforob/promises-guzzle-php

## Dummy Json
https://dummyjson.com/docs

# Uso de Guzzle y HttpClient en Symfony para Peticiones Asíncronas

Este documento explica cómo utilizar Guzzle y el componente HttpClient de Symfony para realizar peticiones HTTP de forma asíncrona utilizando promesas.

## Instalación

### Guzzle
Para instalar Guzzle en tu proyecto Symfony, ejecuta:

```sh
composer require guzzlehttp/guzzle
```

### Symfony HttpClient
Si prefieres usar el componente HttpClient de Symfony, instálalo con:

```sh
composer require symfony/http-client
```

## Uso de Guzzle para Peticiones Asíncronas

Guzzle permite realizar peticiones asíncronas mediante promesas utilizando el método `requestAsync`.

```php
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

$client = new Client();

$promise1 = $client->requestAsync('GET', 'https://jsonplaceholder.typicode.com/posts/1');
$promise2 = $client->requestAsync('GET', 'https://jsonplaceholder.typicode.com/posts/2');

$results = Promise\settle([$promise1, $promise2])->wait();

foreach ($results as $result) {
    if ($result['state'] === 'fulfilled') {
        echo $result['value']->getBody();
    } else {
        echo "Error: " . $result['reason']->getMessage();
    }
}
```

## Uso de HttpClient de Symfony para Peticiones Asíncronas

El componente HttpClient de Symfony permite ejecutar peticiones concurrentes usando el método `stream()`.

```php
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Response\StreamableInterface;

$client = HttpClient::create();

$responses = [
    'post1' => $client->request('GET', 'https://jsonplaceholder.typicode.com/posts/1'),
    'post2' => $client->request('GET', 'https://jsonplaceholder.typicode.com/posts/2'),
];

foreach ($client->stream($responses) as $response => $chunk) {
    if ($chunk->isLast()) {
        echo $responses[array_search($response, $responses)]->getContent();
    }
}
```
