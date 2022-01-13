<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/author")
 */
class AuthorController extends AbstractController //ce controller sert à gérer les auteurs du blog: afficher la liste de
    //tout les auteurs, modifier les infos d'un auteur et supprimer un auteur
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/", name="author_index", methods={"GET"})
     */
    public function index(AuthorRepository $authorRepository): Response //cette fonction sert à recupérer la liste de
        //tout les auteurs du blog et les afficher à partir de la vue author/index.html.twig.
        // Elle n'est accéssiblequ'aux admins
    {
        return $this->render('author/index.html.twig', [
            'authors' => $authorRepository->findAll(),
        ]);
    }

    /**
     *  @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="author_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response  //cette focntion sert à créer un nouvel
        //auteur dans le blog à partir de la vue author/new.html.twig Cette méthode n'est accéssible que si l'utilisateur
        //possède le rôle admin
    {
        $author = new Author(); //instanciation de la classe Author
        $form = $this->createForm(AuthorType::class, $author); //création d'un formulaire de type AuthorType
        $form->handleRequest($request);  //recupération de la requête http

        if ($form->isSubmitted() && $form->isValid()) { //si le formulaire est soumis et remplis correctement
            // la création du nouvel auteur est confirmée
            //et on réoriente vers la page author/index.html.twig qui affiche la liste de tout les auteurs
            $entityManager->persist($author); //l'appel de cette méthode 'persist' nous sert à communiqué avec la table author de
            //la base de données à travers l'interface entitymanager de l'orm doctrine
            $entityManager->flush();

            return $this->redirectToRoute('author_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('author/new.html.twig', [
            'author' => $author,
            'form' => $form->createView(),
        ]); //création d'une vue author/new.html.twig de type AuthorType
    }

    /**
     * @Route("/{id}", name="author_show", methods={"GET"})
     */
    public function show(Author $author): Response //cette fonction sert à afficher les détails d'un auteur en
        // utilisant la vue author/show.html.twig
    {
        return $this->render('author/show.html.twig', [
            'author' => $author,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/edit", name="author_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Author $author, EntityManagerInterface $entityManager): Response
    { //cette fonction sert à modifier les infos d'un auteur (par son id) déjà existant dans la bd à partir de la vue author/edit.html.twig et
        //n'est accéssible qu'aux users possédant le rôle admin
        $form = $this->createForm(AuthorType::class, $author); //création d'un formulaire de type AuthorType
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { //si le formulaire est soumis et remplis correctement,
            // la modification des infos de l'auteur est confirmée
            //et on réoriente vers la page author/index.html.twig qui affiche la liste de tout les auteurs
            $entityManager->flush();

            return $this->redirectToRoute('author_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('author/edit.html.twig', [
            'author' => $author,
            'form' => $form->createView(),
        ]); //création d'une vue author/edit.html.twig de type AuthorType
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="author_delete", methods={"POST"})
     */
    public function delete(Request $request, Author $author, EntityManagerInterface $entityManager): Response
    { //cette fonction sert à supprimer un auteur (par son id) de la base de donnée. Cette méthode
        // n'est accéssible qu'aux admins
        if ($this->isCsrfTokenValid('delete'.$author->getId(), $request->request->get('_token'))) {
            $entityManager->remove($author);
            $entityManager->flush();
        }

        return $this->redirectToRoute('author_index', [], Response::HTTP_SEE_OTHER);
    }
}
