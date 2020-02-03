<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        if($this->checkConnection() == true){
            return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('AppBundle:Task')->findAll()]);
        }
        $this->addFlash('error', "Vous n'êtes pas autorisé à accèder à cette page");
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/tasks/done", name="task_list_done")
     */
    public function listDoneAction()
    {
        if($this->checkConnection() == true){
            return $this->render('task/listDone.html.twig', ['tasks' => $this->getDoctrine()->getRepository('AppBundle:Task')->findAll()]);
        }
        $this->addFlash('error', "Vous n'êtes pas autorisé à accèder à cette page");
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request)
    {
        if($this->checkConnection() == true){
            $task = new Task();
            $form = $this->createForm(TaskType::class, $task);

            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                // ******** Add User link with a task ******** //
                $task->setUser($this->get('security.token_storage')->getToken()->getUser());

                $em->persist($task);
                $em->flush();

                $this->addFlash('success', 'La tâche a été bien été ajoutée.');

                return $this->redirectToRoute('task_list');
            }

            return $this->render('task/create.html.twig', ['form' => $form->createView()]);
        }
        $this->addFlash('error', "Vous n'êtes pas autorisé à accèder à cette page");
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request)
    {
        if($this->checkConnection() == true){
            $user = $this->get('security.token_storage')->getToken()->getUser();
            if($user == $task->getUser() or $user->getRoles()[0] == "ROLE_ADMIN"){
                $form = $this->createForm(TaskType::class, $task);

                $form->handleRequest($request);

                if ($form->isValid()) {
                    $this->getDoctrine()->getManager()->flush();

                    $this->addFlash('success', 'La tâche a bien été modifiée.');

                    return $this->redirectToRoute('task_list');
                }

                return $this->render('task/edit.html.twig', [
                    'form' => $form->createView(),
                    'task' => $task,
                ]);
            }
            $this->addFlash('error', "Vous n'êtes pas autorisé à accèder à cette page");

            return $this->redirectToRoute('task_list');
        }
        $this->addFlash('error', "Vous n'êtes pas autorisé à accèder à cette page");
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        if($this->checkConnection() == true){
            $task->toggle(!$task->isDone());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

            return $this->redirectToRoute('task_list');
        }
        $this->addFlash('error', "Vous n'êtes pas autorisé à accèder à cette page");
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/tasks/{id}/toggleCancel", name="task_toggle_cancel")
     */
    public function toggleCancelTaskAction(Task $task)
    {
        if($this->checkConnection() == true){
            $task->toggle(!$task->isDone());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme à faire', $task->getTitle()));

            return $this->redirectToRoute('task_list_done');
        }
        $this->addFlash('error', "Vous n'êtes pas autorisé à accèder à cette page");
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task)
    {
        if($this->checkConnection() == true){
            $user = $this->get('security.token_storage')->getToken()->getUser();
            if($user == $task->getUser() or $user->getRoles()[0] == "ROLE_ADMIN") {
                $em = $this->getDoctrine()->getManager();
                $em->remove($task);
                $em->flush();

                $this->addFlash('success', 'La tâche a bien été supprimée.');

                return $this->redirectToRoute('task_list');
            }
            $this->addFlash('error', "Vous n'êtes pas autorisé à effectuer cette action");

            return $this->redirectToRoute('task_list');
        }
        $this->addFlash('error', "Vous n'êtes pas autorisé à accèder à cette page");
        return $this->redirectToRoute('login');
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
