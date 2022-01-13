<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="users")
     */
    public function index(UserRepository $user): Response //cette fonction permet de recupérer la liste des utilisateurs
        // et l'afficher dans le fichier index.html.twig
    {

        return $this->render('admin/index.html.twig',['users' => $user->findAll()]);

    }

    /**
     * @Route("/index/modifier/{id}", name="modifier_utilisateur")
     */
    public function editUser(Request $request, User $user, EntityManagerInterface $em, $id, UserRepository $userRepo)//cette fonction sert à modifier un
        // utilisateur à partir du formulaire editUser.html.twig de type EditUserType
    {
        $form = $this->createForm(EditUserType::class, $user); //création d'un formulaire du type EditUserType
        $form->handleRequest($request); //recupération de la requêtte http
        if($form->isSubmitted() && $form->isValid()) { //si le formulaire est soumis et remplis correctement
            // la modification est confirme et on réoriente vers la page index.html.twig
            // de l'admin qui affiche la liste des users
            $em->flush();
            return $this->redirectToRoute('index');
        }
        return $this->render('admin/editUser.html.twig', ['user'=>$userRepo->find($id),'formUser'=>$form->createView()]);
    }
}
