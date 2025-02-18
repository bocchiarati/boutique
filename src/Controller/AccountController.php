<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordUserType;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    #[Route('/compte/modifier_mdp', name: 'app_edit_psw')]
    public function password(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher) : Response{

        $user = $this->getUser();
        $pswForm = $this->createForm(PasswordUserType::class,$user, [
            'passwordHasher' => $passwordHasher,
        ]);
        $pswForm->handleRequest($request);
        if ($pswForm->isSubmitted() && $pswForm->isValid()) {
            $em->flush();
            $this->addFlash(
                'success',
                "Votre Mot de passe a bien été modifier !"
            );

            return $this->redirectToRoute('app_account');
        }
        return $this->render('account/password.html.twig', [
            'pswForm' => $pswForm->createView(),
        ]);
    }
}
