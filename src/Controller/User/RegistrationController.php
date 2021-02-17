<?php

namespace App\Controller\User;

use App\Classes\Enum\EnumFlash;
use App\Controller\Common\BaseController;
use App\Entity\User;
use App\Form\User\RegistrationForm;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends BaseController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Encode le mot de passe brut
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            //Envoie de l'email de confirmation
            if ($this->getParameter('USER_VERIFICATION_EMAIL')) {
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->to($user->getEmail())
                        ->subject($this->trans('email.subject.verify_register'))
                        ->htmlTemplate('emails/user/register_confirmation.html.twig')
                );
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render('forms/user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register/verify/email", name="app_verify_email")
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        if ($user->isVerified()) {
            $this->addFlashTrans(EnumFlash::WARNING, 'user.account.verified_already');
            return $this->redirectToRoute('app_login');
        }

        //Validate le lien de confirmation et met l'utilisateur en vérifié
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlashTrans(EnumFlash::ERROR, $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        $this->addFlashTrans(EnumFlash::SUCCESS, 'user.account.verified_success');

        return $this->redirectToRoute('app_login');
    }
}
