<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Entity\Image;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Form\ProduitType;

class HomeController extends AbstractController
{   
    /**
     * @Route("/base", name="base")
     */
    public function base()
    {
        return $this->render('base.html.twig');
    }

     /**
     * @Route("/layout", name="layout")
     */
    public function layout()
    {
        return $this->render('home/layout.html.twig');
    }


    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }



    /**
     * @Route("/accueil", name="accueil")
     */
    public function accueil()
    {
        return $this->render('home/accueil.html.twig');
    }



    /**
     * @Route("/voir/{id}", name="voir",
       requirements={"id"="\d+"}
     )
     */
   /* public function voir($id)
    {
       return $this->render('home/voir.html.twig', [
           'id' =>$id,
        ]);
    }*/

     public function voir($id){

 $repository = $this->getDoctrine()->getManager()->getRepository((Produit::class));
 $prod = $repository->find($id);

 if (null === $prod) {
 throw new NotFoundHttpException("Le produit ayant l'id ".$id."
 n'existe pas.");
 }
 return $this->render('home/voir.html.twig', [
 'prod' => $prod,
 ]);
}



    /**
     * @Route("/ajouter", name="ajouter")
     */
   /* public function ajouter()
    {
        return $this->render('home/ajouter.html.twig');
    }*/

    public function ajouter(Request $request){

        $prod= new Produit();
        $date="2021-01-01";
        $prod->setExpiresAt(new\Datetime($date));
            
       /* $form= $this->createFormBuilder($prod)
         ->add('title', TextType::class)
         ->add('company', TextType::class)
         ->add('description', TextareaType::class)
         ->add('isActivated', CheckboxType::class)
         ->add('expiresAt', DateType::class)
         ->add('email', TextType::class , array('required'=> false))
         ->add('save', SubmitType::class)
         ->getForm(); */

         $form= $this->get('form.factory')
                  ->create(ProduitType::class,$prod)
                  ->add('save', SubmitType::class);


         if($request->isMethod('POST'))
         {
            $form->handleRequest($request);
            if($form->isValid())
            {
                $em= $this->getDoctrine()->getManager();
                $em->persist($prod);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice','produit bien enregistré.');
                return $this->redirectToRoute('voir', array('id' => $prod->getId()));

            }
         }

         return $this->render('home/ajouter.html.twig',
            array('form'=>$form->createView()));
     
    
    }

 /*  public function ajouter()
    {   
         $prod= new produit();
         $prod->setTitle('Médicaments');
         $prod->setCompany('Paris');
         $prod->setDescription("Médicaments enti douleur tableaux A ");
         $prod->setIsActivated(1);
         $prod->setExpiresAt(new\Datetime());
         $prod->setEmail("paris@gmail.com");

         $image= new image();
         $image->setUrl('../../images/lapin.jpg');
         $image->setAlt('Medicaments');
         $prod->setImage($image);

         $em=$this->getDoctrine()->getManager();
         $em->persist($prod);
         $em->
         flush();
        return $this->render('home/ajouter.html.twig',array('prod'=>$prod));
      
    }  */
    /*  public function ajouter()
    {   
         $date="2021-01-01";

          $em=$this->getDoctrine()->getManager();

          $job1=$em->getRepository(Produit::class)->find(1);
          $job1->setExpiresAt(new\Datetime($date));

         $job2= new produit();
         $job2->setTitle('hygiéne');
         $job2->setCompany('france');
         $job2->setDescription("Produit hygiéne ");
         $job2->setIsActivated(1);
         $job2->setExpiresAt(new\Datetime($date));
         $job2->setEmail("france@gmail.com");

        
         $em->persist($job2);
         $em->
         flush();
        return $this->render('home/ajouter.html.twig',array('id1' => $job1->getId(),'id2' =>$job2->getId()));
} */




     /**
* @Route("/modifier/{id}", name="modifier", requirements={"id"="\d+"})

*/

/*public function modifier($id)
    {
        return $this->render('home/modifier.html.twig', [
            'id' => $id,
        ]);
    }*/
        public function modifier($id,Request $request){

        $prod= $this->getDoctrine()
                    ->getManager()
                    ->getRepository(Produit::class)
                    ->find($id);
       
            
      /*  $form= $this->createFormBuilder($prod)
         ->add('title', TextType::class)
         ->add('company', TextType::class)
         ->add('description', TextareaType::class)
         ->add('isActivated', CheckboxType::class, array('required'=> false))
         ->add('expiresAt', DateType::class)
         ->add('email', TextType::class , array('required'=> false))
         ->add('save', SubmitType::class)
         ->getForm(); */
          $form= $this->get('form.factory')
                  ->create(ProduitType::class,$prod)
                  ->add('save', SubmitType::class);


         if($request->isMethod('POST'))
         {
            $form->handleRequest($request);
            if($form->isValid())
            {
                $em= $this->getDoctrine()->getManager();
                $em->persist($prod);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice','Produit bien modifié.');
                return $this->redirectToRoute('voir', array('id' => $prod->getId()));

            }
         }

         return $this->render('home/modifier.html.twig',
            array('form'=>$form->createView()));
     
    
    }
 


     /**
* @Route("/supprimer/{id}", name="supprimer", requirements={"id"="\d+"})

*/
public function supprimer($id)
    {
        return $this->render('home/supprimer.html.twig', [
            'id' => $id,
        ]);
    }

  
    public function menu()
    { $listProduit= array(
    ['id'=>1,'intitule'=>'Médicaments'],
    ['id'=>2,'intitule'=>'Hygiéne'],
    ['id'=>3,'intitule'=>'Bien etre'],
    ['id'=>4,'intitule'=>'Produit Solaire'],
    ['id'=>5,'intitule'=>'Sirop'],
    ['id'=>6,'intitule'=>'AntiDouleur'],
    ['id'=>7,'intitule'=>'Antiinflamatoire']);


  return $this->render('home/menu.html.twig', [
    'listProduit' =>$listProduit,
  ]);
    
  }

  


        
}
