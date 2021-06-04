<?php


namespace App\Tests\Services;

use App\Entity\Produit;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use App\Service\PanierService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;

final class PanierServiceTest extends TestCase
{
    //Test de la suppression d'un produti
    public function testPanierSupprimerProduit(){

        //Création des bouchons pour les objets instancé
        $session = $this->createMock(Session::class);
        $entityManager = $this->createMock(EntityManager::class);
        $repositoryProduit = $this->createMock(ProduitRepository::class);
        $repositoryCommande = $this->createMock(CommandeRepository::class);

        //On défini la méthode get de la session qui doit retourner un tableau idProduit => quantité
        $session->method('get')->willreturn([1=>3,2=>1]);
        //La méthode set doit être appelé une seule fois avec les paramètres iDProduit => quantité pour le panier
        $session->expects($this->exactly(1))->method('set')->with('panier',[1=>3]);

        //
        $panierService = new PanierService($session,$repositoryProduit,$repositoryCommande,$entityManager);
        $panierService->supprimerProduit(2);
    }

    //Provider pour tester enlever
    public function enleverProvider()
    {
        return [
            [[1=>3,2=>2], [1=>3,2=>1]],
            [[1=>3,2=>1], [1=>3]],
        ];
    }


    //Test enlever produit
    /**
     * @dataProvider enleverProvider
     */
    public function testEnleverProduit($a,$b){

        //Création des bouchons pour les objets instanciés
        $session = $this->createMock(Session::class);
        $entityManager = $this->createMock(EntityManager::class);
        $repositoryProduit = $this->createMock(ProduitRepository::class);
        $repositoryCommande = $this->createMock(CommandeRepository::class);

        //On défini la méthode get de la session qui doit retourner un tableau idProduit => quantité
        $session->method('get')->willreturn($a);

        //La méthode set doit être appelé une seule fois avec les paramètres iDProduit => quantité pour le panier
        $session->expects($this->exactly(1))->method('set')->with('panier',$b);

        //
        $panierService = new PanierService($session,$repositoryProduit,$repositoryCommande,$entityManager);
        $panierService->enleverProduit(2);

    }

    //Provider pour tester enlever
    public function ajouterProvider()
    {
        return [
            [[1=>3,2=>2], [1=>3,2=>3]],
            [[1=>3], [1=>3,2=>1]],
        ];
    }


    //Test enlever produit
    /**
     * @dataProvider ajouterProvider
     */
    public function testAjouterProduit($a,$b){

        //Création des bouchons pour les objets instanciés
        $session = $this->createMock(Session::class);
        $entityManager = $this->createMock(EntityManager::class);
        $repositoryProduit = $this->createMock(ProduitRepository::class);
        $repositoryCommande = $this->createMock(CommandeRepository::class);

        //On défini la méthode get de la session qui doit retourner un tableau idProduit => quantité
        $session->method('get')->willreturn($a);

        //La méthode set doit être appelé une seule fois avec les paramètres iDProduit => quantité pour le panier
        $session->expects($this->exactly(1))->method('set')->with('panier',$b);

        //
        $panierService = new PanierService($session,$repositoryProduit,$repositoryCommande,$entityManager);
        $panierService->ajouterProduit(2);

    }


    public function testVider(){
        //Création des bouchons pour les objets instanciés
        $session = $this->createMock(Session::class);
        $entityManager = $this->createMock(EntityManager::class);
        $repositoryProduit = $this->createMock(ProduitRepository::class);
        $repositoryCommande = $this->createMock(CommandeRepository::class);

        //On défini la méthode get de la session qui doit retourner un tableau idProduit => quantité
        $session->method('get')->willreturn([1=>3,2=>2]);

        //La méthode set doit être appelé une seule fois avec les paramètres iDProduit => quantité pour le panier
        $session->expects($this->exactly(1))->method('set')->with('panier',[]);

        //
        $panierService = new PanierService($session,$repositoryProduit,$repositoryCommande,$entityManager);
        $panierService->vider();
    }

    public function testGetContenu(){

        //Création des bouchons pour les objets instanciés
        $session = $this->createMock(Session::class);
        $entityManager = $this->createMock(EntityManager::class);
        $repositoryProduit = $this->createMock(ProduitRepository::class);
        $repositoryCommande = $this->createMock(CommandeRepository::class);

        $produit = new Produit();

        $entityManager->method('getRepository')->willReturn($repositoryProduit);
        $repositoryProduit->method('find')->willReturn($produit);

        $panierService = new PanierService($session,$repositoryProduit,$repositoryCommande,$entityManager);

        //On défini la méthode get de la session qui doit retourner un tableau idProduit => quantité
        $session->method('get')->willreturn([1=>3,2=>4]);

        $this->assertEquals(
            [['produit'=>$produit,'quantite'=>3],['produit'=>$produit,'quantite'=>4]],
            $panierService->getContenu()
        );
    }

}