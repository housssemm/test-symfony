<?php

namespace App\Controller;

use App\Entity\Etudient;
use App\Form\EtuType;
use App\Repository\EtudientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
class EtudientController extends AbstractController
{
    #[Route('/etudient', name: 'app_etudient')]
    public function index(): Response
    {
        return $this->render('etudient/index.html.twig', [
            'controller_name' => 'EtudientController',
        ]);
    }
     #[Route("/afficher", name:"affiche_app")]
    public function data(EtudientRepository $er):Response{
        $list=$er->findAll();
        return $this->render("etudient/afficher.html.twig", ['list'=>$list]);

    }
    #[Route('/ajouter',name:'etud_app')]
    public function ajouter(ManagerRegistry $doctrine, Request $request):response
    {
        $etu=new Etudient();
        $form = $this->createForm(EtuType::class, $etu);
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
        $em=$doctrine->getManager(); 
        $em->persist($etu); 
        $em->flush();
        return $this->redirectToRoute('affiche_app');
        }
             return $this->render('etudient/ajouter.html.twig', [
            'form' => $form->createView()
        ]);
    }
        //delete data
        #[route('/delete/{id}',name:'app_delete')]
public function delete(EtudientRepository $er,int $id,EntityManagerInterface $entityManager):Response{
    $etu=$er->find($id);
    $entityManager->remove($etu);
     $entityManager->flush();
    return $this->redirectToRoute('affiche_app');

}
 #[Route('/Update/{id}',name:'etu_update')]
    public function update(ManagerRegistry $doctrine,Request $request,$id,EtudientRepository $etu):response
    {
        $etud=$etu->find($id);
        $form=$this->createForm(EtuType::class,$etud);
        $form->handleRequest($request);
       if ($form->isSubmitted() )
       {
        $em=$doctrine->getManager(); 
        $em->flush();
        return $this->redirectToRoute('affiche_app');
    }
    return $this->render('etudient/update.html.twig',['form'=>$form->createView()]) ;
    
    }
}
