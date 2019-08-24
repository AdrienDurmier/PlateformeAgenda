<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * https://symfony.com/doc/master/testing.html#working-with-the-test-client
 */
class SecurityControllerTest extends WebTestCase
{
    public function testHome()
    {
        fwrite(STDOUT, "\n" . __METHOD__ . "\t");
        
        $client = static::createClient();
        $client->request('GET', '/');
        // Dans tous les cas il y aura une redirection donc il faut vérifier que le code 302 est bien retouné
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}