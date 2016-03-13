<?php

use Goutte\Client;
use Mockery\MockInterface;
use Ob\Bandwidth\InvalidCredentials;
use Ob\Bandwidth\Isp\Videotron;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Form;

class VideotronTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client|MockInterface
     */
    private $client;

    /**
     * @var Crawler|MockInterface
     */
    private $crawler;

    /**
     * @var Form|MockInterface
     */
    private $form;

    /**
     * @var Videotron
     */
    private $isp;

    public function setUp()
    {
        $this->client  = Mockery::mock(Client::class);
        $this->crawler = Mockery::mock(Crawler::class);
        $this->form    = Mockery::mock(Form::class);
        $this->isp     = new Videotron($this->client, 'username', 'password');
    }

    public function testCanBeCreated()
    {
        $this->assertInstanceOf(Videotron::class, $this->isp);
    }

    public function testCanParseTheBillingPeriod()
    {
        $this->login();
        $this->logout();

        $this->client->shouldReceive('request')->with('GET', Videotron::URL_BANDWIDTH)->andReturn($this->crawler);
        $this->crawler->shouldReceive('filter')->andReturn($this->crawler);
        $this->crawler->shouldReceive('first')->andReturn($this->crawler);
        $this->crawler->shouldReceive('text')->andReturn('Usage from  March 3 to April 2, 2016');

        $usage = $this->isp->getBandwidthUsage();

        $this->assertEquals('2016-03-03', $usage->periodStart()->format('Y-m-d'));
        $this->assertEquals('2016-04-02', $usage->periodEnd()->format('Y-m-d'));
    }

    public function testCanParseTheBandwidthUsage()
    {
        $this->login();
        $this->logout();

        $this->client->shouldReceive('request')->with('GET', Videotron::URL_BANDWIDTH)->andReturn($this->crawler);
        $this->crawler->shouldReceive('filter')->andReturn($this->crawler);
        $this->crawler->shouldReceive('first')->andReturn($this->crawler);
        $this->crawler->shouldReceive('text')->andReturn('24.4 / 130 GB');

        $usage = $this->isp->getBandwidthUsage();

        $this->assertEquals(24.4, $usage->usedBandwidth());
        $this->assertEquals(130, $usage->allottedBandwidth());
    }

    public function testThrowsAnExceptionForInvalidCredentials()
    {
        $this->client->shouldReceive('request')->once()->with('GET', Videotron::URL_LOGIN)->andReturn($this->crawler);

        $this->crawler->shouldReceive('selectButton')->once()->with('Connexion')->andReturn($this->crawler);
        $this->crawler->shouldReceive('form')->once()->andReturn($this->form);

        $this->client->shouldReceive('submit')->once()->with($this->form, [
            'codeUtil'   => 'username',
            'motDePasse' => 'password',
        ])->andReturn($this->crawler);

        $this->crawler->shouldReceive('filter')->once()->with('.error, .msg-error')->andReturn($this->crawler);
        $this->crawler->shouldReceive('count')->once()->andReturn(1);

        $this->setExpectedException(InvalidCredentials::class);

        $this->isp->getBandwidthUsage();
    }

    private function login()
    {
        $this->client->shouldReceive('request')->once()->with('GET', Videotron::URL_LOGIN)->andReturn($this->crawler);

        $this->crawler->shouldReceive('selectButton')->once()->with('Connexion')->andReturn($this->crawler);
        $this->crawler->shouldReceive('form')->once()->andReturn($this->form);

        $this->client->shouldReceive('submit')->once()->with($this->form, [
            'codeUtil'   => 'username',
            'motDePasse' => 'password',
        ])->andReturn($this->crawler);

        $this->crawler->shouldReceive('filter')->once()->with('.error, .msg-error')->andReturn($this->crawler);
        $this->crawler->shouldReceive('count')->once()->andReturn(0);
    }

    private function logout()
    {
        $this->client->shouldReceive('request')->once()->with('GET', Videotron::URL_LOGOUT);
    }
}
