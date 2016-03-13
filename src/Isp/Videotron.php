<?php

namespace Ob\Bandwidth\Isp;

use Goutte\Client;
use Ob\Bandwidth\InternetServiceProvider;

final class Videotron implements InternetServiceProvider
{
    const URL_LOGIN     = 'https://www.videotron.com/client/residentiel/Espace-client';
    const URL_BANDWIDTH = 'https://www.videotron.com/client/residentiel/secur/CIUserSecurise.do';
    const URL_LOGOUT    = 'https://www.videotron.com/client/user-management/residentiel/secur/Logout.do?dispatch=logout';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @param Client $client
     * @param string $username
     * @param string $password
     */
    public function __construct(Client $client, $username, $password)
    {
        $this->client   = $client;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @throws \Exception
     */
    public function login()
    {
        $crawler = $this->client->request('GET', self::URL_LOGIN);

        $form = $crawler->selectButton('Connexion')->form();

        $crawler = $this->client->submit($form, [
            'codeUtil'   => $this->username,
            'motDePasse' => $this->password,
        ]);

        $crawler->filter('.error, .msg-error')->each(function ($node) {
            $message = trim($node->text());

            // TODO: throw a domain exception
            throw new \Exception($message);
        });
    }

    public function logout()
    {
        $this->client->request('GET', self::URL_LOGOUT);
    }

    public function getBandwidthUsage()
    {
        $crawler = $this->client->request('GET', self::URL_BANDWIDTH);

        $title      = $crawler->filter('#titre_consommation h3')->first()->text();
        $lastUpdate = $crawler->filter('#titre_consommation .details_mise_a_jour')->first()->text();
        $usage      = $crawler->filter('.quantities')->first()->text();
        $ratio      = $crawler->filter('.progress_bar')->first()->text();

        // TODO: replace with a VO
        return [
            'title'   => trim($title),
            'updated' => trim($lastUpdate),
            'usage'   => trim($usage),
            'ratio'   => trim($ratio),
        ];
    }
}
