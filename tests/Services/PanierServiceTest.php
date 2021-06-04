<?php


namespace App\Tests\Services;

use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use App\Service\PanierService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;

final class TestPanierService extends TestCase
{
    public function testPanierVider(){

        $session = $this->createMock(\Symfony\Component\HttpFoundation\Session);
        $session->method('set')->willreturn(['product_id'=>12,'qty'=>3])
            ->expects($this->exactly(1))->method('set')->with('panier',[]);

        $entityManager = $this->createMock(EntityManagerInterface);

        $panier = new PanierService($session,ProduitRepository::class,CommandeRepository::class, $entityManager);
        $panier->supprimerProduit(2);
    }



}