<?php

namespace Blogger\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogsControllerTest extends WebTestCase
{
    public function testShow()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/show');
    }

}
