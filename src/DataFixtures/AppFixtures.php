<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //créer les catégories
        $arrayCategories = [];
        for ($count = 0; $count < 5; $count++) {
            $categorie = new Categorie();
            $categorie->setNom("Catégorie".$count);
            $categorie->setTexte("description de la catégorie".$count);
            $categorie->setVisuel("lien de l'image de la catégorie".$count);
            $arrayCategories[$count] = $categorie;
            $manager->persist($categorie);
        }

        //ajout des produits dans les catégories
        for ($count = 0; $count < 5; $count++) {
            for ($countProduit = 0; $countProduit < 7; $countProduit++) {
                $produit = new Produit();
                $produit->setVisuel("visuel du produit".$countProduit);
                $produit->setTexte("texte du produit".$countProduit);
                $produit->setCategorie($arrayCategories[$count]);
                $produit->setLibelle("produit".$countProduit);
                $produit->setPrix($countProduit);
                $manager->persist($produit);
            }
        }

        //Créer un user
        $user = new User();
        $user->setNom("User de test");
        $user->setEmail("test@test.com");
        $user->setPassword(" ");
        $user->setPrenom("Test");
        $manager->persist($user);




        $manager->flush();
    }


}
