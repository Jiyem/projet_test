<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Entity\User;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Service\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class PanierController extends AbstractController
{
private $entityManager;

    /**
     * PanierController constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/panier", name="panier")
     */
    public function index(PanierService $panierService): Response
    {
        //recuperation du contenu du panier avec la fonction getContenu() du panierService
        $monPanier= $panierService->getContenu();

        //calcul du prix total du panier avec la fonction getTotal() du panierService
        $total= $panierService->getTotal();

        return $this->render('panier/index.html.twig', [
            'contenus' => $monPanier,
            'total' => $total
        ]);
    }


    /**
     * @Route("/panier/ajouter/{id}", name="panierAjouter")
     */
    public function ajouter(int $id, PanierService $panierService){

        //on appelle la methode ajouterProduit du service en lui passant l'id
        $panierService->ajouterProduit($id);

       //redirection vers le panier
        return $this->redirectToRoute('panier');

    }


    /**
     * @Route("/panier/supprimer/{id}", name="panierSuppr")
     */
    public function supprimer(int $id, PanierService $panierService){
        $panierService->supprimerProduit($id);

        return $this->redirectToRoute('panier');

    }

    /**
     * @Route("/panier/supprimer", name="panierVider")
     */
    public function vider( PanierService $panierService){
        $panierService->vider();

        return $this->redirectToRoute('panier');

    }


    /**
     * @Route("/panier/retraitPanier/{id}", name="panierRetirer")
     */
    public function retirer(int $id, PanierService $panierService){

        $panierService->enleverProduit($id);

        return $this->redirectToRoute('panier');

    }


    /**
     * @Route("/commande", name="maCommande")
     */
    public function commande(PanierService $panierService ){

        $maCommande= $panierService->panierToCommande($this->getUser());

        return $this->render('commande/index.html.twig', [
            'maCommande' => $maCommande
        ]);
    }
}