<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 * Class StatsController
 * @package App\Controller\Admin
 */
class StatsController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/dashboard", name="admin_dashboard")
     * @return Response
     */
    public function index(): Response
    {
        $monthy_new_register_users_goal = 10;   //en pourcentage

        return $this->render('admin/pages/stats.html.twig', [
            'total_users' => $this->userRepository->count([]),
            'total_users_confirmed' => $this->userRepository->count(['isVerified' => true]),
            'monthly_users' => $this->userRepository->countUsersRegisterThisMonth(),
            'monthy_new_register_users_goal' => $monthy_new_register_users_goal,
        ]);
    }

}
