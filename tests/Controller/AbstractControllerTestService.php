<?php


namespace App\Tests\Controller;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractControllerTestService extends WebTestCase
{

    /**
     * @var KernelBrowser
     */
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
