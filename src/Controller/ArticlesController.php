<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/articles")
 */
class ArticlesController extends AbstractController //ce controller sert à gérer les articles du blog: afficher la liste de
    //tout les articles, modifier un article et supprimer un article
{
    /**
     * @Route("/", name="articles_index", methods={"GET"})
     */
    public function index(ArticlesRepository $articlesRepository): Response //cette fonction sert à recupérer la liste de
        //tout les articles du blog et les afficher à partir de la vue articles/index.html.twig
    {
        return $this->render('articles/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]);
    }

    /**
     *  @IsGranted("ROLE_EDITOR")
     * @Route("/new", name="articles_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response //cette focntion sert à créer un nouvel
        //article dans le blog à partir de la vue articles/new.html.twig Cette méthode n'est accéssible que si l'utilisateur
        //possède le rôle éditeur, c'est à dire aux auters et à l'admin
    {
        $article = new Articles(); //instanciation de la classe Articles
        $form = $this->createForm(ArticlesType::class, $article); //création d'un formulaire de type ArticlesType
        $form->handleRequest($request); //recupération de la requête http

        if ($form->isSubmitted() && $form->isValid()) {//si le formulaire est soumis et remplis correctement
            // la création du nouvel article est confirmée
            //et on réoriente vers la page articles/index.html.twig qui affiche la liste de tout les articles
            $entityManager->persist($article); //l'appel de cette méthode 'persist' nous sert à communiqué avec la table articles de
            //la base de données à travers l'interface entitymanager de l'orm doctrine
            $entityManager->flush();

            return $this->redirectToRoute('articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('articles/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]); //création d'une vue articles/new.html.twig de type ArticlesType
    }

    /**
     * @Route("/{id}", name="articles_show", methods={"GET"})
     */
    public function show(Articles $article): Response //cette méthode sert à afficher les détails d'un article en
        // utilisant la vue articles/show.html.twig
    {
        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     *  @IsGranted("ROLE_EDITOR")
     * @Route("/{id}/edit", name="articles_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Articles $article, EntityManagerInterface $entityManager): Response
    {   //cette méthode sert à modifier un article (par son id) déjà existant dans la bd à partir de la vue articles/edit.html.twig et
        //n'est accéssible qu'aux users possédant le rôle éditeur, c'est à dire aux auters et à l'admin
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { //si le formulaire est soumis et remplis correctement,
            // la modification de l'article est confirmée
            //et on réoriente vers la page articles/index.html.twig qui affiche la liste de tout les articles
            $entityManager->flush();

            return $this->redirectToRoute('articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]); //création d'une vue articles/edit.html.twig de type ArticlesType
    }

    /**
     *  @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="articles_delete", methods={"POST"})
     */
    public function delete(Request $request, Articles $article, EntityManagerInterface $entityManager): Response
    {   //cette méthode sert à supprimer un article (par son id) de la base de donnée. Cette méthode
        // n'est accéssible qu'aux admins
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('articles_index', [], Response::HTTP_SEE_OTHER);
    }
}
