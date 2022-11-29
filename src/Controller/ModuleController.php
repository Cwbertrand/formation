<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Programme;
use App\Form\ModuleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/module', name: 'module')]
    public function index(): Response
    {
        $form = $this->em->getRepository(Programme::class)->findBy([], ['nbjours' => 'ASC']);

        return $this->render('module/index.html.twig', [
            'forms' => $form,
        ]);
    }


    #[Route('/module/add', name: 'add_module')]
    #[Route('/module/{id}/edit', name: 'edit_module')]
    public function addModule(Module $module = null, Request $request)
    {
        if(!$module){
            $module = new Module();
        }
        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $module = $form->getData();
            $this->em->persist($module);
            $this->em->flush();
            
            $this->addFlash('success', 'Successfully added');
            return $this->redirectToRoute('add_module');
        }
        
        return $this->render('module/addmodule.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/module/{id}/delete', name: 'delete_module')]
    public function deleteSession(Module $module): Response
    {
        $this->em->remove($module);
        $this->em->flush();
        return $this->redirectToRoute('module');
    }

    #[Route('/module/{id}', name: 'show_module')]
    public function showModule(Module $module): Response
    {
        
        return $this->render('module/showmodule.html.twig', [
            'detaileModule' => $module,
        ]);
    }
}
