<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

 /**
     * @Route("/participant", name="")
     */
class ParticipantController extends AbstractController
{
    /**
     * @Route("/", name="app_participant")
     */
    public function index(): Response
    {
        return $this->render('participant/index.html.twig', [
            'controller_name' => 'ParticipantController',
        ]);
    }

    /**
     * @Route("/Ajouter",name="ajouter")
     */
    public function ajouter(Request $request,EntityManagerInterface $entityManger)
    {
        $participant=new Participant();
        $form=$this->createForm(ParticipantType::class,$participant);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManger->persist($participant);
            $entityManger->flush();
          
           
            return $this->redirectToRoute("Participant_home");
        }
   

        return $this->render('participant/ajouter.html.twig',[
            'participant_form'=> $form->createView()
        ]);
    }
    
    /**
     * @Route("/home",name="Participant_home")
     */
    public function home()
    {
        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository(Participant::class);
        $lesParticipants=$repo->findAll();
        return $this->render("participant/home.html.twig",[
            'lesParticipants'=>$lesParticipants
        ]);
    }
    /**
     *@Route("/delete/{id}",name="participant_delete")
     */
    public function delete(Request $request,$id):Response
    {
        $p=$this->getDoctrine()
            ->getRepository(Participant::class)
            ->find($id);
            if(!$p){
                throw $this->createNotFoundException(
                    'No participant found for id'.$id
                );
            }
            $entityManger=$this->getDoctrine()->getManager();
            $entityManger->remove($p);
            $entityManger->flush();
            return $this->redirectToRoute("Participant_home");
    }

    /**
     * @Route("/editP/{id}",name="editP")
     * Method({"GET","POST"})
     */
    public function edit(Request $request,$id)
    {
        $participant=new Participant();
        $participant=$this->getDoctrine()->getRepository(Participant::class)
                          ->find($id);
        if(!$participant){
            throw $this->createNotFoundException(
                'No participant found for id'.$id
            );
        }
        $form=$this->createForm(ParticipantType::class,$participant);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $entityManger=$this->getDoctrine()->getManager();
            $entityManger->flush();
            return $this->redirectToRoute("Participant_home");
        }
        return $this->render('participant/ajouter.html.twig',
        [
            "participant_form"=>$form->createView()
        ]);

    }

}
