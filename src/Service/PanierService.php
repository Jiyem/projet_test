<?php

namespace App\Service;

use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Entity\Produit;
use App\Entity\User;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class PanierService
{


    private $session; // Le service Session
    protected $produitRepository;
    protected $commandeRepository;
    protected $userRepository;
    private $entityManager;


    // constructeur du service
    public function __construct(SessionInterface $session, ProduitRepository $produitRepository, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager)
    {

        // Récupération des services session et BoutiqueService
        $this->session = $session;

        $this->entityManager = $entityManager;

        //pour utiliser le produitRepository et acceder aux produits
        $this->produitRepository = $produitRepository;

        //pour utiliser le produitRepository et acceder aux produits
        $this->commandeRepository = $commandeRepository;


    }

    // ajouterProduit ajoute au panier le produit $id
    public function ajouterProduit(int $id)
    {

        $panier = $this->session->get('panier', []);

        //si un produit avec cet id est deja present ds panier alors +1:
        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $this->session->set('panier', $panier);
    }


    // getContenu renvoie le contenu du panier
    public function getContenu(): array
    {
        //panier en session
        $panier = $this->session->get('panier', []);

        //panier avec le detail des produits
        $monPanier = [];

        //boucle sur le panier pour extraire l'id du produit et la quantité et remplir monpanier
        foreach ($panier as $id => $quantite) {

            $produit = $this->entityManager->getRepository(Produit::class)->find($id);

            //tableau de couple qui contient le produit et sa quantité
            $monPanier[] = [
                //recuperation du produit associé à l'id dans le panier en session
                'produit' => $produit,
                'quantite' => $quantite
            ];
        }
        return $monPanier;
    }


    // getTotal renvoie le montant total du panier
    public function getTotal(): float
    {

        $total = 0;

        //recuperation $monPanier en faisant appel a la fonction ci-dessus getContenu
        $monPanier = $this->getContenu();

        foreach ($monPanier as $contenu) {

            //calcul du prix total du panier
            $total += $contenu['produit']->getPrix() * $contenu['quantite'];
        }
        return $total;

    }


    // supprimerProduit supprime complètement le produit $id du panier
    public function supprimerProduit(int $id)
    {
        $panier = $this->session->get('panier', []);

        //si il existe cet id ds mon panier alors je le supprime
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        //recuperation du panier maj
        $this->session->set('panier', $panier);
    }


    // vider vide complètement le panier
    public function vider()
    {
        $this->session->set('panier', []);

    }


    // enleverProduit enlève du panier le produit $id
    public function enleverProduit(int $id)
    {

        $panier = $this->session->get('panier', []);

        if ($panier[$id] > 1) {
            //retirer un produit -1
            $panier[$id]--;
        } else {
            //fonction unset: supprimer un element d'un tableau
            unset($panier[$id]);
        }
        return $this->session->set('panier', $panier);

    }

    public function panierToCommande(User $user)
    {

        //initialisation du champ dateCreation
        $dateCreation = new \DateTime();

        //recuperation du contenu du panier
        $monPanier = $this->getContenu();

        //Creation de la commande pour le client connécte
        $commande = new Commande();

        //recuperation du user
        $commande->setUser($user);

        //recuperation de la date
        $commande->setDateCreation($dateCreation);

        // Figer/persister la donnée user pour l'enregistrement
        $this->entityManager->persist($commande);

        // enregistrement / maj de la donnée en bd
        $this->entityManager->flush();

        //Pour chaque produit du panier creation d'une nouvelle entrée dans ligneCommande
        foreach ($monPanier as $produit) {

            $ligneCommandes = new LigneCommande($commande, $produit);
            $ligneCommandes->setCommande($commande);
            $ligneCommandes->setProduit($produit['produit']);
            $ligneCommandes->setQuantite($produit['quantite']);
            $ligneCommandes->setPrice($produit['produit']->getPrix());
            $ligneCommandes->setTotal($produit['produit']->getPrix() * $produit['quantite']);
            $this->entityManager->persist($ligneCommandes);

            // dd($ligneCommandes);
        }

        $this->entityManager->flush();
        $this->vider();

        return  $commande;

    }
}

/*
            // getNbProduits renvoie le nombre de produits dans le panier
            public function getNbProduits() { // à compléter }





*/


