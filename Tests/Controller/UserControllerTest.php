<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        // Create a new client to browse the application
        $this->client = static::createClient();
    }

    public function testCompleteScenario()
    {
        $this->logIn();

        // Create a new entry in the database
        $crawler = $this->client->request('GET', '/admin/user/list');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /admin/user/list");
        $crawler = $this->client->click($crawler->selectLink('Create a new user')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form([
            'lracicot_user_manager_bundle_create[username]'  => 'Test',
            'lracicot_user_manager_bundle_create[email]'  => 'Test@example.com',
            'lracicot_user_manager_bundle_create[plainPassword]'  => 'Test',
        ]);

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $this->client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Edit')->form([
            'lracicot_user_manager_bundle_edit[username]'  => 'Foo',
            'lracicot_user_manager_bundle_edit[email]'  => 'Test2@example.com',
        ]);

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="Foo"]')->count(), 'Missing element [value="Foo"]');

        // Delete the entity
        $this->client->submit($crawler->selectButton('Delete')->form());
        $crawler = $this->client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $this->client->getResponse()->getContent());
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        // the firewall context defaults to the firewall name
        $firewallContext = 'secured_area';

        $token = new UsernamePasswordToken('admin', null, $firewallContext, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
