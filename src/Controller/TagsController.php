<?php

namespace App\Controller;

use App\Entity\Tags;
use App\Form\TagsType;
use App\Repository\TagsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tags")
 */
class TagsController extends AbstractController //ce controller sert à gérer les tags du blog: afficher la liste de
    //tout les tags, modifier ub tag et supprimer un tag
{
    /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/", name="tags_index", methods={"GET"})
     */
    public function index(TagsRepository $tagsRepository): Response //cette fonction sert à recupérer la liste de
        //tout les tags du blog et les afficher à partir de la vue tags/index.html.twig. Elle n'est accéssible
        // qu'aux users possédants le role éditeur
    {
        return $this->render('tags/index.html.twig', [
            'tags' => $tagsRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="tags_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    { //cette focntion sert à créer un nouveau
        //tag dans le blog à partir de la vue tags/new.html.twig Cette méthode n'est accéssible que si l'utilisateur
        //possède le rôle admin
        $tag = new Tags();
        $form = $this->createForm(TagsType::class, $tag); //création d'un formulaire de type TagsType
        $form->handleRequest($request); //recupération de la requête http

        if ($form->isSubmitted() && $form->isValid()) { //si le formulaire est soumis et remplis correctement
            // la création du nouvel auteur est confirmée
            //et on réoriente vers la page tags/index.html.twig qui affiche la liste de tout les tags
            $entityManager->persist($tag); //l'appel de cette méthode 'persist' nous sert à communiqué avec la table tags de
            //la base de données à travers l'interface entitymanager de l'orm doctrine
            $entityManager->flush();

            return $this->redirectToRoute('tags_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tags/new.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]); //création d'une vue tags/new.html.twig de type TagsType
    }

    /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/{id}", name="tags_show", methods={"GET"})
     */
    public function show(Tags $tag): Response //cette fonction sert à afficher les détails d'un tag en
        // utilisant la vue tags/show.html.twig
    {
        return $this->render('tags/show.html.twig', [
            'tag' => $tag,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/edit", name="tags_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tags $tag, EntityManagerInterface $entityManager): Response
    { //cette fonction sert à modifier les détails d'un tag (par son id) déjà existant dans la bd à partir de la vue tags/edit.html.twig et
        //n'est accéssible qu'aux users possédant le rôle admin
        $form = $this->createForm(TagsType::class, $tag); //création d'un formulaire de type TagsType
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { //si le formulaire est soumis et remplis correctement,
            // la modification du tag est confirmée
            //et on réoriente vers la page tags/index.html.twig qui affiche la liste de tout les tags
            $entityManager->flush();

            return $this->redirectToRoute('tags_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tags/edit.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]); //création d'une vue tags/edit.html.twig de type TagsType
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="tags_delete", methods={"POST"})
     */
    public function delete(Request $request, Tags $tag, EntityManagerInterface $entityManager): Response
    { //cette fonction sert à supprimer un tag (par son id) de la base de données. Cette méthode
        // n'est accéssible qu'aux admins
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tag);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tags_index', [], Response::HTTP_SEE_OTHER);
    }
}
