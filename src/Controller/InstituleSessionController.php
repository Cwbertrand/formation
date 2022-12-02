<?php

namespace App\Controller;

use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\ProgrammeType;
use App\Entity\InstituleSession;
use App\Form\InstituleSessionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class InstituleSessionController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/institulesession', name: 'instituleSession')]
    public function index(): Response
    {
        $form = $this->em->getRepository(InstituleSession::class)->findBy([], ['themesession' => 'ASC']);
        
        return $this->render('institule_session/index.html.twig', [
            'forms' => $form,
        ]);
    }

    //add and edit session
    #[Route('/institulesession/add', name: 'add_instituleSession')]
    #[Route('/institulesession/{id}/edit', name: 'edit_instituleSession')]
    public function addSession(InstituleSession $instituleSession = null, Request $request)
    {
        if(!$instituleSession){
            $instituleSession = new InstituleSession();
        }

        $form = $this->createForm(InstituleSessionType::class, $instituleSession);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $instituleSession = $form->getData();
            $this->em->persist($instituleSession);
            $this->em->flush();

            return $this->redirectToRoute('add_instituleSession');
        }
        
        return $this->render('institule_session/addsession.html.twig', [
            'form' => $form->createView(),
            'edit' => $instituleSession->getId(),
        ]);
    }

    #[Route('/institulesession/{id}/delete', name: 'delete_instituleSession')]
    public function deleteSession(InstituleSession $instituleSession): Response
    {
        $this->em->remove($instituleSession);
        $this->em->flush();
        return $this->redirectToRoute('instituleSession');
    }

    //Show details of session
    #[Route('/institulesession/{id}', name: 'show_instituleSession')]
    public function showSession(Programme $programme = null, InstituleSession $institulesession = null, Request $request)
    {
        $noninscrit = $this->em->getRepository(Stagiaire::class)->findNonInscrit($institulesession->getId());

        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $programme = $form->getData();
            $programme->setInstituleSession($institulesession);
            $this->em->persist($programme);
            $this->em->flush();
            
            return $this->redirectToRoute('show_instituleSession', ['id' => $institulesession->getId()]);
        }

        return $this->render('institule_session/showsession.html.twig', [
            'form' => $form->createView(),
            'sessiondetails' => $institulesession,
            'noninscrits' => $noninscrit,
        ]);
    }
    
    // Add programme and edit
    #[ParamConverter('institulesession', options: ['mapping' => ['idinstitulesession' => 'id']])]
    #[ParamConverter('programme', options: ['mapping' => ['idprogramme' => 'id']])]
    #[Route('/programme/add', name: 'add_programme')]
    #[Route('/programme/edit/{idprogramme}/{idinstitulesession}', name: 'edit_programme')]
    public function addProgramme(Programme $programme = null, InstituleSession $institulesession= null, Request $request)
    {

        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $programme = $form->getData();
                $programme->setInstituleSession($institulesession);
                $this->em->persist($programme);
                $this->em->flush();
                return $this->redirectToRoute('show_instituleSession', ['id' => $institulesession->getId()]);
            }
        
        return $this->render('institule_session/addprogramme.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * doctrineconverter options
     * The ParamConverter annotation calls converters to convert request parameters to objects. 
     * These objects are stored as request attributes and so they can be injected as controller
     * mapping: Configures the properties and values to use with the findOneBy() method: 
     * the key is the route placeholder name and the value is the Doctrine property name:
    */

    //Inscrit un stagiaire
    // Annotration method
    // /**
    //  * @ParamConverter{'institulesession', options={'mapping': {'idinstitulesession': 'id'}}}
    //  * @ParamConverter{'stagiaire', options={'mapping': {'idstagiaire': 'id'}}}
    //  */
    //Attribute method
    #[ParamConverter('institulesession', options: ['mapping' => ['idinstitulesession' => 'id']])]
    #[ParamConverter('stagiaire', options: ['mapping' => ['idstagiaire' => 'id']])]
    #[Route('/institulesession/inscrire/{idinstitulesession}/{idstagiaire}', name: 'inscrire_stagiaire')]
    public function inscrireStagiaire(InstituleSession $institulesession, Stagiaire $stagiaire): Response
    {
        if($institulesession->getPlaceRestant() > 0){
            $institulesession->addStagiaire($stagiaire);
            
            $this->em->flush();
        }
        
        $noninscrit = $this->em->getRepository(Stagiaire::class)->findNonInscrit($institulesession->getId());
        return $this->render('institule_session/showsession.html.twig', [
            'sessiondetails' => $institulesession,
            'noninscrits' => $noninscrit,
        ]);
    }


    //desinscrit un stagiaire
    #[ParamConverter('institulesession', options: ['mapping' => ['idinstitulesession' => 'id']])]
    #[ParamConverter('stagiaire', options: ['mapping' => ['idstagiaire' => 'id']])]
    #[Route('/institulesession/desinscrire/{idinstitulesession}/{idstagiaire}', name: 'desinscrire_stagiaire')]
    public function desinscrireStagiaire(InstituleSession $institulesession, Stagiaire $stagiaire): Response
    {
        $institulesession->removeStagiaire($stagiaire);
        $this->em->flush();
        $noninscrit = $this->em->getRepository(Stagiaire::class)->findNonInscrit($institulesession->getId());
        return $this->render('institule_session/showsession.html.twig', [
            'sessiondetails' => $institulesession,
            'noninscrits' => $noninscrit,
        ]);
    }


    //Delete le programme
    #[ParamConverter('institulesession', options: ['mapping' => ['idinstitulesession' => 'id']])]
    #[ParamConverter('programme', options: ['mapping' => ['idprogramme' => 'id']])]
    #[Route('/programme/delete/{idprogramme}/{idinstitulesession}', name: 'delete_programme')]
    public function deleteProgramme(Programme $programme = null, InstituleSession $institulesession): Response
    {
        $this->em->remove($programme);
        $this->em->flush();
        return $this->redirectToRoute('show_instituleSession', ['id' => $institulesession->getId()]);
    }


}
