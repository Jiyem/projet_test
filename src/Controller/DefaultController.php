<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController {



    public function hello($userName) {
        return $this->render('hello.html.twig' , [
            'controller_name'=> 'DefaultController',
            'userName' => $userName,
        ]);
    }

    public function affichageListeProduit(){
        return $this-> render('ListeProduit.html.twig',[
            "titre" => "Nos produits",
        ] );
    }

    public function affichageProduitDetail($nomProduit){
        return $this-> render('produitDetail.html.twig',[
            "titre" => $nomProduit,
        ] );
    }

    public function contact(){
        return $this -> render('contact.html.twig',[
            "titre" => "Contact",
        ]);
    }


}