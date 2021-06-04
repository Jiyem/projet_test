<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    /**
     * RegisterController constructor.
     * @param $entityManager
     */


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/inscription", name="inscription")
     */

    public function index(Request $request,UserPasswordEncoderInterface $encoder ): Response
    {
        //Instanciation user:
        $user = new User();

        //création du formulaire:
        $form = $this ->createForm(RegisterType::class, $user);

        //Le formulaire ecoute la requete entrante + dependance request
        $form ->handleRequest($request);

        //
        if($form ->isSubmitted() && $form->isValid()){

            $user = $form->getData();

            //recuperation du password pour encodage
            $password =$encoder->encodePassword($user,$user->getPassword());


            //stockage du password encodé dans l'objet user
            $user->setPassword($password);

           // Figer/persister la donnée user pour l'enregistrement
            $this->entityManager->persist($user);

           // enregistrement / maj de la donnée en bd
            $this->entityManager->flush();

        //dd($user);
        }

        return $this->render('register/index.html.twig', [

            //passage du formulaire a la vue
            'form' => $form->createView(),
        ]);
    }
}
