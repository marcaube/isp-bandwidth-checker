<?php

use Dotenv\Dotenv;
use Goutte\Client;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv(__DIR__);
$dotenv->load();
$dotenv->required(['USERNAME', 'PASSWORD']);

$client  = new Client();
$crawler = $client->request('GET', 'https://www.videotron.com/client/residentiel/Espace-client');

$form    = $crawler->selectButton('Connexion')->form();
$crawler = $client->submit($form, [
    'codeUtil'   => getenv('USERNAME'),
    'motDePasse' => getenv('PASSWORD'),
]);

$crawler->filter('.error, .msg-error')->each(function ($node) {
    echo 'Error: ' . trim($node->text()) . "\n";
    exit();
});

$crawler = $client->request('GET', 'https://www.videotron.com/client/residentiel/secur/CIUserSecurise.do');

$crawler->filter('.error, .msg-error')->each(function ($node) {
    echo 'Error: ' . trim($node->text()) . "\n";
    exit();
});

$crawler->filter('#titre_consommation h3')->each(function ($node) {
    echo trim($node->text()) . "\n";
});

$crawler->filter('#titre_consommation .details_mise_a_jour')->each(function ($node) {
    echo trim($node->text()) . "\n";
});

$crawler->filter('.quantities')->each(function ($node) {
    echo trim($node->text()) . "\n";
});

$crawler->filter('.progress_bar')->each(function ($node) {
    echo trim($node->text()) . "\n";
});

// Really important to logout, or else the maximum number of sessions will be exceeded!
$crawler = $client->request('GET', 'https://www.videotron.com/client/user-management/residentiel/secur/Logout.do?dispatch=logout');
