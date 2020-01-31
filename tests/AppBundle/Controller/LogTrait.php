<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use AppBundle\Entity\User;
use AppBundle\Entity\Task;

trait LogTrait
{
    private $client;
    /**
     * Se logger en tant qu'administrateur
     */
    private function logInAdmin()
    {
        $session = $this->client->getContainer()->get('session');
        $firewallName = 'secured_area';
        $token = new UsernamePasswordToken('Admin', null, $firewallName, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewallName, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
    /**
     * Se logger en tant qu'utilisateur
     */
    private function logInUser()
    {
        $session = $this->client->getContainer()->get('session');
        $firewallName = 'secured_area';
        $token = new UsernamePasswordToken('user', null, $firewallName, array('ROLE_USER'));
        $session->set('_security_'.$firewallName, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}