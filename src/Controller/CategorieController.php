<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CategorieController extends AbstractController
{   

    #[Route('/categorie', name: 'app_categorie')]
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }

    #[Route('/categorie', name: 'app_categorie')]
    public function liste(EntityManagerInterface $entityManager): Response
    {
        
        $repository = $entityManager->getRepository(Categorie::class);
        
        $categories = $repository->findAll();
        

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);

    }

    #[Route('/categorie/new', name: 'app_category_new')]
    public function new(EntityManagerInterface $entityManager) : Response
    {

        $categorie = new Categorie;
        $categorie->setNom('Policier');
        $categorie->setDescription("Article Policier");
        
        $entityManager->persist($categorie);
        $entityManager->flush();

        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);

    }

    #[Route('/categorie/ajouter', name: 'app_categorie_ajouter')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        //Etape 1 : On créé une nouvelle instance de la classe Categorie
        $categorie = new Categorie();
        //Etape 2 :Création du formulaire via la classe CategorieType
        $form = $this->createForm(CategorieType::class, $categorie);
        //Etape 3 : Traitement de la requête HTTP par le formulaire
        $form->handleRequest($request);
        //Etape 4 : On vérifie si le formulaire à été soumis et si il est valide !
        if ($form->isSubmitted() && $form->isValid() ) {
            //On récupère les données soumises dans le formulaire
            $categorie = $form->getData();

            //Sauvegarde de la categorie dans la db
            $entityManager->persist($categorie);
            $entityManager->flush();

                //On redirige vers la page categorie
                return $this->redirectToRoute('app_categorie', [
                    
                ]);
        }

        // On retourne le rendu du formulaire dans le template s'il n'est pas soumis ou s'il n'est pas valide
        return $this->render('categorie/ajouter.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/categorie/modifier/{id}', name: 'app_categorie_modifier')]
    public function update(Request $request, EntityManagerInterface $entityManager, Categorie $categorie): Response
    {
        //Etape 2 :Création du formulaire via la classe CategorieType
        $form = $this->createForm(CategorieType::class, $categorie);
        //Etape 3 : Traitement de la requête HTTP par le formulaire
        $form->handleRequest($request);
        //Etape 4 : On vérifie si le formulaire à été soumis et si il est valide !
        if ($form->isSubmitted() && $form->isValid() ) {
            
            $entityManager->flush();

                //On redirige vers la page avec mes categories
                return $this->redirectToRoute('app_categorie', [
                    'id' => $categorie->getId()
                ]);
        }

        // On retourne le rendu du formulaire dans le template s'il n'est pas soumis ou s'il n'est pas valide
        return $this->render('categorie/modifier.html.twig', [
            'form' => $form,
        ]);
    }
}
