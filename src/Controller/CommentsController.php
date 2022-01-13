<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comments")
 */
class CommentsController extends AbstractController //ce controller sert à gérer les commentaires du blog: afficher la liste de
    //tout les commentaires, modifier un commentaires et supprimer un commentaires
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/", name="comments_index", methods={"GET"})
     */
    public function index(CommentsRepository $commentsRepository): Response //cette fonction sert à recupérer la liste de
        //tout les commentaires du blog et les afficher à partir de la vue comments/index.html.twig. Cette méthode n'est accéssible que si l'utilisateur
        //possède le rôle admin
    {
        return $this->render('comments/index.html.twig', [
            'comments' => $commentsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="comments_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    { //cette focntion sert à créer un nouveau commentaire dans le blog à partir de la vue comments/new.html.twig
        $comment = new Comments(); //instanciation de la classe Comments
        $form = $this->createForm(CommentsType::class, $comment); //création d'un formulaire de type CommentsType
        $form->handleRequest($request); //recupération de la requête http

        if ($form->isSubmitted() && $form->isValid()) {//si le formulaire est soumis et remplis correctement
            // la création du nouveau commentaire est confirmée
            //et on réoriente vers la page comments/index.html.twig qui affiche la liste de tout les auteurs
            $entityManager->persist($comment);//l'appel de cette méthode 'persist' nous sert à communiqué avec la table comments de
            //la base de données à travers l'interface entitymanager de l'orm doctrine
            $entityManager->flush();

            return $this->redirectToRoute('comments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comments/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]); //création d'une vue comments/new.html.twig de type CommentsType
    }

    /**
     * @Route("/{id}", name="comments_show", methods={"GET"})
     */
    public function show(Comments $comment): Response //cette méthode sert à afficher les détails d'un commentaire en
        // utilisant la vue comments/show.html.twig
    {
        return $this->render('comments/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comments_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Comments $comment, EntityManagerInterface $entityManager): Response
    { //cette méthode sert à modifier un commentaires (par son id) déjà existant
        // dans la bd à partir de la vue comments/edit.html.twig
        $form = $this->createForm(CommentsType::class, $comment); //création d'un formulaire de type CommentsType
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { //si le formulaire est soumis et remplis correctement,
            // la modification du commentaire est confirmée
            $entityManager->flush();

        }

        return $this->render('comments/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]); //création d'une vue comments/edit.html.twig de type CommentsType
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="comments_delete", methods={"POST"})
     */
    public function delete(Request $request, Comments $comment, EntityManagerInterface $entityManager): Response
    { //cette fonction sert à supprimer un commentaire (par son id) de la base de donnée. Elle
        // n'est accéssible qu'aux admins
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comments_index', [], Response::HTTP_SEE_OTHER);
    }
}
