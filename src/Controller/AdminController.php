<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
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
}
