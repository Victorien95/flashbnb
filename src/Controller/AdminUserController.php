<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user_index")
     */
    public function index(UserRepository $repo)
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("admin/user/{id}/edit", name="admin_user_edit")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(User $user, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($user);

            $manager->flush();

            $this->addFlash('success', "L'utilisateur <strong>n°{$user->getId()} a bien été modifié</strong>");

        }


        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet de supprimer un utilisateur
     *
     * @Route("admin/user/{id}/delete", name="admin_user_delete")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(User $user, EntityManagerInterface $manager)
    {
        $userId = $user->getId();
        $manager->remove($user);

        $manager->flush();

        $this->addFlash('success', "L'utilisateur <strong>n°{$userId} a bien été supprimé</strong>");

        return $this->redirectToRoute('admin_user_index');
    }
}
