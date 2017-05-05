<?php

namespace lracicot\FOSUserManagerBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 */
class DefaultController extends Controller
{
    /**
     * Lists all user entities.
     */
    public function indexAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        return $this->render('@lracicotFOSUserManager/CRUD/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * Creates a new user entity.
     */
    public function newAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();

        $form = $this->createForm('lracicot\FOSUserManagerBundle\Form\UserCreateType', $user);
        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEnabled(true);
            $userManager->updateUser($user);

            return $this->redirectToRoute('lracicot_fos_user_manager_show', ['userId' => $user->getId()]);
        }

        return $this->render('@lracicotFOSUserManager/CRUD/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a user entity.
     */
    public function showAction($userId)
    {
        $user = $this->findUserById($userId);
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('@lracicotFOSUserManager/CRUD/show.html.twig', [
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing user entity.
     */
    public function editAction(Request $request, $userId)
    {
        $user = $this->findUserById($userId);
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('lracicot\FOSUserManagerBundle\Form\UserEditType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lracicot_fos_user_manager_edit', ['userId' => $user->getId()]);
        }

        return $this->render('@lracicotFOSUserManager/CRUD/edit.html.twig', [
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a user entity.
     */
    public function deleteAction(Request $request, $userId)
    {
        $user = $this->findUserById($userId);
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('lracicot_fos_user_manager_list');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param UserInterface $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UserInterface $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('lracicot_fos_user_manager_delete', ['userId' => $user->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Find a user by id.
     *
     * @param int $id property value
     *
     * @return UserInterface
     */
    protected function findUserById($id)
    {
        return $this->get('fos_user.user_manager')->findUserBy(['id' => $id]);
    }
}
