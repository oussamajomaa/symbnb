<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $lastUser = $utils->getLastUsername();
        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'lastUser' => $lastUser
        ]);
    }

    /**
     * Undocumented function
     *
     * @Route("/logout", name="account_logout")
     * @return Response
     */
    public function logout()
    {
        // rien....
    }

    /**
     * permet d'afficher le formulaire d'inscription
     *
     * @Route("/register", name="account_register")
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user->setHash($encoder->encodePassword($user, $user->getHash()));
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "Votre compte a bien été enregistré");
            return $this->redirectToRoute('account_login');
        }
        return $this->render("account/register.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * permet de modifier le profil d'un utilisateur
     *
     * @Route("/account/profile",name="account_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile(Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "Votre profil a bien été modifié");
        }
        return $this->render("account/profile.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * permet de modifier le mot de passe
     *
     * @Route("/account/password", name="account_password")
     * @IsGranted("ROLE_USER")
     */
    public function updatePassword(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        // récupérer le user connecté
        $user = $this->getUser();
        // instancier la classe PasswordUpdate (( la classe qui ressemble à une entité ))
        $passwordUpdate = new PasswordUpdate();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // récupérer l'ancien mot de passe saisi dans le formulaire
            $oldPassword = $passwordUpdate->getOldPassword();

            // récupérer le mot de passe hashé dans la BDD
            $hash = $user->getHash();
            // comparer l'ancien mot de passe saisi dans le formulaire avec celui de la table User

            if (!password_verify($oldPassword, $hash)) {
                // on récupère le champ oldPassword et sur lequel on va ajouter une erreur
                $form->get("oldPassword")->addError(new FormError("Le mot de passe que vous avez tapé n'est pas le bon !"));

            } else {
                // récupérer le nouveau mot de passe saisi dans le formulaire
                $newPassword = $passwordUpdate->getNewPassword();
                // hasher le nouveau mot de passe

                $user->setHash($encoder->encodePassword($user, $newPassword));
                // persister le nouveau mot de passe dans la table User
                $manager->persist($user);
                $manager->flush();

                $this->addFlash("success", "Votre mot de passe a été mis à jour !");
                return $this->redirectToRoute("homepage");
            }
        }
        return $this->render("account/password.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * permet d'afficher le profil d'un utilisateur
     * @Route("/account",name="account_index")
     * @IsGranted("ROLE_USER")
     */
    public function myAccount()
    {
        return $this->render("user/index.html.twig",[
            "user" => $this->getUser()
        ]);
    }

    /**
     * permet d'afficher les bookings faites par l'utilisateur
     * @Route("/account/bookings", name="account_bookings")
     * @IsGranted("ROLE_USER")
     */
    public function bookings()
    {
        return $this->render('account/bookings.html.twig');
    }
}
