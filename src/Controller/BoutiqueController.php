<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoutiqueController extends AbstractController
{
   //Pour recuperer ma liste des categories j'utilise entityManager

    private $entityManager;

    /**
     * BoutiqueController constructor.
     * @param $entityManager
     */


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/boutique", name="boutique")
     */
    public function index(): Response
    {
        //Pour recuperer les categories j'utilise la fonction getRepository et findAll():

        $categories = $this ->entityManager->getRepository(Categorie::class)->findAll();

        return $this->render('boutique/index.html.twig', [
            'categories' => $categories,
        ]);
    }


    /**
     * @Route("/rayon/{id}", name="rayon")
     */

    public function show($id): Response
    {
        //Pour recuperer les categories j'utilise la fonction getRepository et findAll():
        $categorie = $this ->entityManager->getRepository(Categorie::class)->find($id);

        //redirection vers la route /boutique si la categorie n'est pas trouvée
        if (!$categorie){
           return $this->redirectToRoute('boutique');
        }

        //redirection vers le template de la liste des produits de la categorie
        return $this->render('product/index.html.twig', [
            'produits' => $categorie->getProduits(),
        ]);
    }


    /**
     * @Route("/produit/{id}", name="produit")
     */

    public function showProduct($id): Response
    {
        //Pour recuperer les categories j'utilise la fonction getRepository et findAll():
        $produit = $this ->entityManager->getRepository(Produit::class)->find($id);

        //redirection vers la route /boutique si la categorie n'est pas trouvée
        if (!$produit){
            return $this->redirectToRoute('boutique');
        }

        //redirection vers le template show fiche produit
        return $this->render('product/show.html.twig', [
            'produit' => $produit,
        ]);
    }
}
