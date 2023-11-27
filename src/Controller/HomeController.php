<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{   
    /*
    #[Route('/', name: 'app_home')]
    public function showByPk(Article $articles): Response
    {

        
        return $this->render('home/index.html.twig', [
            'articles' => $articles,
            
            // dans mon twig on peut toujours utiliser article.id(.titre,.contenu, etc)
        ]);

    }*/

    #[Route('/', name: 'app_home')]
    public function listeArticle(EntityManagerInterface $entityManager): Response
    {
        //
        $repository = $entityManager->getRepository(Article::class);
        //ici $repository devient un objet articleRepository
        $articles = $repository->findAll();
        

        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);

    }
}
