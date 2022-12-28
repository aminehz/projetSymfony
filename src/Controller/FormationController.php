<?php

namespace App\Controller;
use App\Entity\Participant;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use SebastianBergmann\Environment\Console;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\FormationType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Formation;
use Doctrine\ORM\EntityManagerInterface;

 /**
     * @Route("/formation", name="")
     */
class FormationController extends AbstractController
{
    /**
     * @Route("/", name="app_formation")
     */
    public function index(): Response
    {
        $entityManger=$this->getDoctrine()->getManager();
        $formation=new Formation();
        $formation->setTitre('python');
        $formation->setPrice(10.0);
        $formation->setDuree(15);
        $formation->setBeginAt(new \DateTimeImmutable());
        $entityManger->persist($formation);
        $entityManger->flush();

        return $this->render('formation/index.html.twig', [
            'id' => $formation->getId(),
        ]);
    }
        /**
         * @Route("/formation/{id}",name="formation")
         */

    public function show($id)
    {
        $formation = $this->getDoctrine()
        ->getRepository(Formation::class)
        ->find($id);

    if (!$formation) {
        throw $this->createNotFoundException(
            'No formation found for id ' . $id
        );
    }
    $em=$this->getDoctrine()->getManager();
    $participant=$em->getRepository(Participant::class);
    $listParticipant=$em
    ->getRepository(Participant::class)
    ->findBy(['Formation'=>$formation]);
  
    return $this->render('formation/show.html.twig', [

        'formation' => $formation,
        'participant'=>$participant,
        'listParticipant'=>$listParticipant
    ]);

    }
    

    /**
     * @Route("/Ajouter",name="ajouter_formation")
     */
    public function ajouter(Request $request,EntityManagerInterface $entityManger)
    {   
        $formation= new Formation();
        $form=$this->createForm(FormationType::class,$formation);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManger->persist($formation);
            $entityManger->flush();
            //return new Response('Formation number'.$formation->getId().'created');
            return $this->redirectToRoute("show");
        }

        return $this->render('formation/ajouter.html.twig',[
            'formation_form'=> $form->createView()
        ]);


    }
    /**
     * @Route("/home",name="show")
     */
    public function home(){
        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository(Formation::class);
        $lesFormations=$repo->findAll();
        return $this->render("formation/home.html.twig",[
            'lesFormations'=>$lesFormations
        ]);
    }

     /**
     *@Route("/delete/{id}",name="formation_delete")
     */
    public function delete(Request $request,$id):Response
    {
        $p=$this->getDoctrine()
            ->getRepository(Formation::class)
            ->find($id);
            if(!$p){
                throw $this->createNotFoundException(
                    'No participant found for id'.$id
                );
            }
            $entityManger=$this->getDoctrine()->getManager();
            $entityManger->remove($p);
            $entityManger->flush();
            return $this->redirectToRoute("show");
    }
   /**
     * @Route("/editF/{id}",name="editF")
     * Method({"GET","POST"})
     */
    public function edit(Request $request,$id)
    {
        $formation=new Formation();
        $formation=$this->getDoctrine()->getRepository(Formation::class)
                          ->find($id);
        if(!$formation){
            throw $this->createNotFoundException(
                'No participant found for id'.$id
            );
        }
        $form=$this->createForm(FormationType::class,$formation);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $entityManger=$this->getDoctrine()->getManager();
            $entityManger->flush();
            return $this->redirectToRoute("show");
        }
        return $this->render('formation/ajouter.html.twig',
        [
            "formation_form"=>$form->createView()
        ]);

    }

}
