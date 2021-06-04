<?php
namespace App\Controller;
use App\Entity\Produit;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController {


    /**
     * @Route("/hello", name="hello")
     */
    public function hello() {
        return $this->render('hello.html.twig');
    }

    public function contact(){
        return $this -> render('contact.html.twig',[
            "titre" => "Contact",
        ]);
    }





}