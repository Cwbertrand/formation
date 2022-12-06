<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $user = $this->em->getRepository(User::class)->findBy([], ['email' => 'ASC']);
        return $this->render('admin/index.html.twig', [
            'users' => $user,
        ]);
    }

    #[Route('/user/{id}/delete', name: 'delete_user')]
    public function deleteUser(User $user): Response
    {
        $this->em->remove($user);
        $this->em->flush();
        return $this->redirectToRoute('admin');
    }

    #[Route('/user/{id}', name: 'show_user')]
    public function showSession(User $user): Response
    {

        return $this->redirectToRoute('compte', ['id' => $user->getId()]);
        // return $this->render('user/showuser.html.twig', [
        //     'detaileuser' => $user,
        // ]);
    }

    #[Route('/user/{id}/edit', name: 'edit_user')]
    public function editUser(User $user, Request $request)
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('message', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin');
        }
        
        return $this->render('admin/edituser.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
