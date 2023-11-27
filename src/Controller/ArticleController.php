<?php

namespace App\Controller;



use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }


    #[Route('/article/new', name: 'app_article_new')]
    public function new(EntityManagerInterface $entityManager) : Response
    {
        $repository = $entityManager->getRepository(Categorie::class);
        //ici $repository devient un objet categorie Repository
        //$categorie = $repository->find(1);
        //dd($categorie);
        $categorie = $repository->findByName('Policier');
        
        $article = new Article;
        $article->setNom('Deuxieme article policier');
        $article->setContenu('contenu policier numero 2');
        $article->setDateDeCreation(new \DateTime('2023-11-23'));
        $article->setCategorie($categorie);
        
        $entityManager->persist($article);
        $entityManager->flush();

        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);

    }


    #[Route('/article/detail/{id}', name: 'app_article_detail')]
    public function detail($id, EntityManagerInterface $entityManager): Response
    {
        //
        $repository = $entityManager->getRepository(Article::class);
        //ici $repository devient un objet articleRepository
        $article = $repository->find($id);
        

        return $this->render('article/detail.html.twig', [
            'article' => $article,
        ]);

    }

    //FORMULAIRE CREATION ARTICLE
    #[Route('/article/ajouter', name: 'app_article_ajouter')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        //Etape 1 : On créé un nouvel Article
        $article = new Article();
        // Etape 2 : forcement on modifie la date à celle d'aujourd'hui vu que l'article sera créé à la date du jour via le formulaire
        $article->setDateDeCreation(new DateTime());
        //Etape 3 :Création du formulaire via la classe ArticleType
        $form = $this->createForm(ArticleType::class, $article);
        //Etape 4 : Traitement de la requête HTTP par le formulaire
        $form->handleRequest($request);
        //Etape 5 : On vérifie si le formulaire à été soumis et si il est valide !
        if ($form->isSubmitted() && $form->isValid() ) {
            //On récupère les données soumises dans le formulaire
            $article = $form->getData();

            //Sauvegarde de l'article dans la db
            $entityManager->persist($article);
            $entityManager->flush();

                //On redirige vers la page détail de l'article qui vient d'être créé
                return $this->redirectToRoute('app_article_detail', [
                    'id' => $article->getId()
                ]);
        }

        // On retourne le rendu du formulaire dans le template s'il n'est pas soumis ou s'il n'est pas valide
        return $this->render('article/ajouter.html.twig', [
            'form' => $form,
        ]);
    }
   

}
