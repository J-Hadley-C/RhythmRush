<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        UrlGeneratorInterface $urlGenerator
    ): Response {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());
            $user->setPassword($hashedPassword);

            // Enregistre le rôle en tant que tableau
            $user->setRoles([$form->get('roles')->getData()]);

            // Génère un token de confirmation
            $confirmationToken = bin2hex(random_bytes(32));
            $user->setConfirmationToken($confirmationToken);
            $user->setIsActive(false); // Assure que l'utilisateur est inactif par défaut

            // Gestion du fichier de photo de profil
            /** @var UploadedFile $photoFile */
            $photoFile = $form->get('photo')->getData();
            
            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                    $user->setPhoto($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de la photo.');
                }
            }

            // Sauvegarde l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Génère l'URL de confirmation d'email
            $confirmationUrl = $urlGenerator->generate('app_confirm_email', [
                'token' => $confirmationToken,
            ], UrlGeneratorInterface::ABSOLUTE_URL);

            $email = (new Email())
                ->from('no-reply@votresite.com')
                ->to($user->getEmail())
                ->subject('Confirmation de votre inscription')
                ->html($this->renderView('register/confirmation_email.html.twig', [
                    'confirmationUrl' => $confirmationUrl,
                ]));

            $mailer->send($email);

            return $this->redirectToRoute('app_confirmation_email');
        }

        return $this->render('register/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/confirmation-email', name: 'app_confirmation_email')]
    public function confirmationEmail(): Response
    {
        return $this->render('register/confirmation_email.html.twig');
    }

    #[Route('/confirm-email/{token}', name: 'app_confirm_email')]
    public function confirmEmail(string $token, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['confirmationToken' => $token]);

        if (!$user) {
            $this->addFlash('error', 'Lien de confirmation invalide ou expiré.');
            return $this->redirectToRoute('app_register');
        }

        $user->setConfirmationToken(null);
        $user->setIsActive(true);
        $entityManager->flush();

        $this->addFlash('success', 'Votre compte a été confirmé avec succès. Vous pouvez maintenant vous connecter.');
        return $this->redirectToRoute('app_login');
    }

#[Route('/select-role', name: 'app_select_role')]
public function selectRole(Request $request, EntityManagerInterface $entityManager): Response
{
    $user = $this->getUser();

    if (!$user) {
        $this->addFlash('error', 'Vous devez être connecté pour sélectionner un rôle.');
        return $this->redirectToRoute('app_login');
    }

    $form = $this->createFormBuilder($user)
        ->add('roles', ChoiceType::class, [
            'choices' => [
                'Artist' => 'ROLE_ARTIST',
                'Producteur' => 'ROLE_PRODUCTEUR',
                'Beatmaker' => 'ROLE_BEATMAKER',
            ],
            'expanded' => true,
            'multiple' => false,
        ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Votre rôle a été mis à jour avec succès.');
        return $this->redirectToRoute('app_dashboard');
    }

    return $this->render('register/select_role.html.twig', [
        'roleForm' => $form->createView(),
    ]);
}
}