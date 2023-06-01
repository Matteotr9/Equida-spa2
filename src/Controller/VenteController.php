<?php

namespace App\Controller;

use ApiPlatform\Serializer\AbstractItemNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CategorieDeVente;
use App\Entity\Vente;
use App\Entity\Client;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Form\VenteType;



class VenteController extends AbstractController
{
    #[Route('/vente/lister', name: 'app_vente_lister')]
    public function getLesVentes(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $ventes = $entityManager->getRepository(Vente::class)->findAll();
       /* $ventes = $this->getDoctrine()
            ->getRepository(Vente::class)
            ->findAll();*/

        return $this->render('vente/lister_ventes.html.twig', [
            'ventes' => $ventes,
        ]);
    }

    #[Route('api/vente/lister', name: 'app_vente_lister_api')
    ]
    public function getLesVentesapi(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $ventes = $entityManager->getRepository(Vente::class)->findAll();
        $jsonContent = [];
        foreach ($ventes as $vente){
            $nomVente= $vente->getNom();
            $dateDebutVente=$vente->getDateDebut();
             $dateFinVente=$vente->getDateFin();
            $libelleCategorieDeVente=$vente->getCategorieDeVentes()->getLibelle();
            $listeVente = ["nomVente"=>$nomVente,"dateDebutVente"=>$dateDebutVente,"dateFinVente"=>$dateFinVente,"libelleCategorieDeVente"=>$libelleCategorieDeVente];
            array_push($jsonContent, $listeVente);
        }
   //**
   //    $encoders = [new XmlEncoder(), new JsonEncoder()];
   //    $normalizers = [new ObjectNormalizer()];

   //    $serializer = new Serializer($normalizers, $encoders);

   //    $jsonContent = $serializer->serialize($ventes, 'json',[AbstractItemNormalizer::IGNORED_ATTRIBUTES=>['categorieDeVentes','lots']]);
     //
                return new JsonResponse($jsonContent)   ;
    }

    #[Route('/vente/consulter/{idvente}', name:'app_vente_consulter')]
    public function consulterVente($idvente, Request $request, ManagerRegistry $doctrine): Response
        {

       // Récupération du cheval correspondant à l'identifiant
             $ventes = $doctrine->getRepository(Vente::class)->find(intval($idvente));
         // Vérification si le cheval existe

         
        
        // Renvoi de la réponse avec les données du cheval
             return $this->render('vente/consulter.html.twig', [
                 'ventes' => $ventes,
    ]);

    }



    #[Route('api/vente/consulter/{id}', name: 'app_vente_get_api', methods: ['GET'])]
    public function getVenteApi(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $vente = $entityManager->getRepository(Vente::class)->find($id);

        if (!$vente) {
            return new JsonResponse(['error' => 'Vente not found'], Response::HTTP_NOT_FOUND);
        }

        $lots = $vente->getLots();

        $jsonContent = [];
        foreach ($lots as $lot) {
            $lotChevalId = $lot->getCheval()->getId();
            $lotChevalNom = $lot->getCheval()->getNom();
            $lotChevalRaceLibelle = $lot->getCheval()->getRace()->getLibellle();
            $lotMiseAPrix= $lot->getMiseAPrix();
            $listelot = ["lotChevalNom" => $lotChevalNom, "lotChevalRaceLibelle" => $lotChevalRaceLibelle, "lotMiseAPrix" => $lotMiseAPrix];
            array_push($jsonContent, $listelot);
        }

        return new JsonResponse($jsonContent);
    }




    #[Route('/vente/nouveau', name:'app_vente_nouveau')]
    
    public function nouveau(Request $request, ManagerRegistry $doctrine): Response
    {

        $vente = new Vente();
        $form = $this->createForm(VenteType::class, $vente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //En attendant la connexion client
            $client= $doctrine->getRepository(Client::class)->find(1);
           

            //$entityManager = $this->getDoctrine()->getManager();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($vente);
            
            $entityManager->flush();

            $this->addFlash('success', 'La vente a été enregistré avec succès.');


            return $this->redirectToRoute('app_vente_consulter', ['idvente' => $vente->getId()]);
        }

        return $this->render('vente/nouveau.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}