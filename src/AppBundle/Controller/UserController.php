<?php

namespace AppBundle\Controller;

use AppBundle\Repository\UserRepository;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    private $error = "Vous n'êtes pas autorisé à accèder à cette page";

    /**
     * @Route("/users", name="user_list")
     */
    public function listAction()
    {
        // check si l'utilisateur est authentifié
        if($this->checkConnection() == true){
            return $this->render('user/list.html.twig', ['users' => $this->getDoctrine()->getRepository('AppBundle:User')->findAll()]);
        }
        $this->addFlash('error', $this->error);
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function createAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $result = $this->checkUser($user->getUsername());
            if($result == true){
                $this->addFlash('error', "Ce nom d'utilisateur existe déjà");
                return $this->render('user/create.html.twig', ['form' => $form->createView()]);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editAction(User $user, Request $request)
    {
        // check si l'utilisateur est authentifié
        if($this->checkConnection() == true){
            $userConnected = $this->get('security.token_storage')->getToken()->getUser();

            // check si l'utilisateur authentifié est le même que celui a édité ou est ADMIN
            if($userConnected == $user or $userConnected->getRoles()[0] == "ROLE_ADMIN") {
                $form = $this->createForm(UserType::class, $user);

                $form->handleRequest($request);

                if ($form->isValid()) {
                    $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
                    $user->setPassword($password);

                    $this->getDoctrine()->getManager()->flush();

                    $this->addFlash('success', "L'utilisateur a bien été modifié");

                    return $this->redirectToRoute('user_list');
                }

                return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
            }
            $this->addFlash('error', $this->error);

            return $this->redirectToRoute('user_list');
        }
        $this->addFlash('error', $this->error);
        return $this->redirectToRoute('login');
    }

    /**
     * @param $username
     * @return bool
     */
    private function checkUser($username)
    {
        $result = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $username]);

        if(!empty($result)){
            return true;
        }
        return false;
    }


    /**
     * @return bool
     */
    private function checkConnection()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return false;
        }
        return true;
    }
}
