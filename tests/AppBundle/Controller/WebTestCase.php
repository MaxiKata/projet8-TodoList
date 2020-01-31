<?php


namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Task
     */
    protected $task;

    /**
     * @var User
     */
    protected $admin;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function loadFixturesForTests()
    {
        $this->em->createQuery('DELETE AppBundle:Task t')->execute();
        $this->em->createQuery('DELETE AppBundle:User u')->execute();

        $user = new User();

        $user->setUsername('User');
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, 'hihi');
        $user->setPassword($encoded);
        $user->setEmail('test@test.com');
        $user->setRoles(['ROLE_USER']);

        $this->em->persist($user);

        $admin = new User();

        $admin->setUsername('Admin');
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');
        $encoded = $encoder->encodePassword($admin, 'hihi');
        $admin->setPassword($encoded);
        $admin->setEmail('testadmin@test.com');
        $admin->setRoles(['ROLE_ADMIN']);

        $this->em->persist($admin);

        $task = new Task();

        $task->setTitle('Ceci est un test');
        $task->setContent('Description du test');
        $task->setCreatedAt(new \DateTime('2020-01-30 12:00:00'));
        $task->toggle(false);
        $task->setUser($user);

        $this->em->persist($task);

        $this->em->flush();

        $this->user     =   $user;
        $this->admin    =   $admin;
        $this->task     =   $task;
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;

        $this->user = null;
        $this->task = null;
    }
}