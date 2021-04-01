<?php

namespace App\Controller;
use App\Entity\Pin;

use  App\Repository\PinRepository;
use  App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Form\PinType;

use App\form\ImageType;
use App\Entity\Image;

use App\form\UserType;
use App\Entity\User;

class PinsController extends AbstractController
{
    /**
     * @Route("/pins", name="pins")
     */
    public function index(PinRepository $pinRepository): Response

    {
    	$pins = $pinRepository->findBy([],['id' => 'DESC']);

        return $this->render('pins/index.html.twig',compact('pins'));
            
    }

     /**
     * @Route("/pins/{id}", name="show",
       requirements={"id"="\d+"},methods={"GET","POST"}
     )
     */
   /*public function show(Pin $pin ): Response
    {
      
      return $this->render('pins/show.html.twig', compact('pin'))  ;     
    }*/

       public function show($id){

        $repository = $this->getDoctrine()->getManager()->getRepository((Pin::class));
             $pin = $repository->find($id);

            if (null === $pin) {
          throw new NotFoundHttpException("Le pin ayant l'id ".$id."
                 n'existe pas.");
                  }
                         return $this->render('pins/show.html.twig', [
                 'pin' => $pin,
                  ]);
                 }

     /**
     * @Route("/pins/create", name="create", methods={"GET","POST"})
     */
    public function create(Request $request, UserRepository $userRepo): Response

    { 
    	$pin = new Pin();
    	
    	/*$form= $this->createFormBuilder($pin)
    	->add('title',TextType::class)
    	->add('description',TextareaType::class)
    	->getForm();*/

    	   $form= $this->get('form.factory')
                  ->create(PinType::class,$pin);
                  
    	
         
            $form->handleRequest($request);

            if ( $form->isSubmitted()  &&$form->isValid())
            {
                $lobna = $userRepo->findOneBy(['email'=>'lobna@gmail.com']);
                $pin->setUser($lobna);
                $pin=$form->getData() ; 
               
                $em=$this->getDoctrine()->getManager();
                $em->persist($pin);
                $em->flush();
                // $request->getSession()->getFlashBag()->add('notice','Pin successfully created !');
                 $this->addFlash('success','Pin successfully created !');
                 return $this->redirectToRoute('pins',array('id' => $pin->getId()));


            }
        

        return $this->render('pins/create.html.twig',['form'=>$form->createView()]);
            
    }

   
   /**
     * @Route("/pins/{id}/edit", name="edit",
       requirements={"id"="\d+"}
     )
     */
   public function edit($id,Request $request): Response
    {


        $pin= $this->getDoctrine()
                    ->getManager()
                    ->getRepository(Pin::class)
                    ->find($id);
       
    	
    	/*$form= $this->createFormBuilder($pin)
    	->add('title',TextType::class)
    	->add('description',TextareaType::class)
    	->getForm();*/

    	 $form= $this->get('form.factory')
                  ->create(PinType::class,$pin);
    	
         
            $form->handleRequest($request);

            if ( $form->isSubmitted()  &&$form->isValid())
            {
                
               $pin=$form->getData() ; 
               
                $em=$this->getDoctrine()->getManager();
                $em->persist($pin);
                $em->flush();
                //$request->getSession()->getFlashBag()->add('notice','Pin successfully updated !');
                 $this->addFlash('success','Pin successfully updated !');

                 return $this->redirectToRoute('pins',array('id' => $pin->getId()));


            }
        
      
      return $this->render('pins/edit.html.twig',['pin'=>$pin,'form'=>$form->createView()]);
                 
    }


    /**
     * @Route("pins/{id}/delete", name="delete", methods={"GET"})
     */
    public function delete( Pin $pin): Response
    {

    	$em=$this->getDoctrine()->getManager();
                $em->remove($pin);
                $em->flush();
                 

        return $this->redirectToRoute('pins');
    }



}
