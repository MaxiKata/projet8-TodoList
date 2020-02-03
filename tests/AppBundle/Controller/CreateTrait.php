<?php

namespace Tests\Appbundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

trait CreateTrait
{
    private $client;

    /**
     *
     */
    public function createUser($role)
    {
        $user = new User;
        $user->setUsername('user'.random_int(1, 10000));
        $user->setEmail('email'.random_int(1, 10000).'@example.com');
        $user->setRoles($role);
        $user->setPassword('hihi');
        $this->client->getContainer()->get('doctrine.orm.entity_manager')->persist($user);
        $this->client->getContainer()->get('doctrine.orm.entity_manager')->flush();
        return $user;
    }

    /**
     * @param $user
     * @return Task
     * @throws \Exception
     */
    public function createTask($user)
    {
        $task = new Task;
        $task->setTitle('Tâche'.random_int(1, 10000));
        $task->setContent('Contenu de la tâche de test.');
        $task->setUser($user);
        $this->client->getContainer()->get('doctrine.orm.entity_manager')->persist($task);
        $this->client->getContainer()->get('doctrine.orm.entity_manager')->flush();
        return $task;
    }
}