<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\ContractController;
use App\Repository\ContractRepository;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

$pdo = new PDO('mysql:host=localhost;dbname=test_contracts', 'test', 'password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);
$request = $creator->fromGlobals();

$controller = new ContractController(new ContractRepository($pdo));
$response = $controller->list($request);

http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
}
}
echo $response->getBody();

