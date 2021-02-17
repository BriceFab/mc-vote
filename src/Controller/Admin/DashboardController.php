<?php

namespace App\Controller\Admin;

use App\Classes\Enum\EnumParamKey;
use App\Controller\Admin\Crud\MenuCrudController;
use App\Controller\Admin\Crud\ParameterCrudController;
use App\Controller\Admin\Crud\ServerCrudController;
use App\Controller\Admin\Crud\UserCrudController;
use App\Service\ParameterService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    private $parameterService;
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator, ParameterService $parameterService)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->parameterService = $parameterService;
    }

    /**
     * @Route("/%ADMIN_PATH%", name="admin")
     */
    public function index(): Response
    {
        return $this->redirect($this->adminUrlGenerator->setRoute('admin_dashboard')->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        $sitename = $this->parameterService->getDatabaseParam(EnumParamKey::SITE_NAME) ?? '';

        return Dashboard::new()
            ->setTitle($sitename)
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        // Stats
        yield MenuItem::linktoDashboard('Statistiques', 'fa fa-bar-chart');

        yield MenuItem::linkToCrud('Gestion des serveurs', 'fas fa-list', ServerCrudController::getEntityFqcn());
        yield MenuItem::linkToCrud('Menus', 'fas fa-list', MenuCrudController::getEntityFqcn());
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-list', UserCrudController::getEntityFqcn());
        yield MenuItem::linkToCrud('Paramètres', 'fas fa-list', ParameterCrudController::getEntityFqcn());
        yield MenuItem::linkToLogout('Logout', 'fa fa-sign-out');
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
             ->addWebpackEncoreEntry("admin_app")
             ->addWebpackEncoreEntry("toast");
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            ->overrideTemplates([
                'layout' => 'admin/override/layout.html.twig',
                'flash_messages' => 'admin/override/flash_messages.html.twig',
            ]);
    }
}
