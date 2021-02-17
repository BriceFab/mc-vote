<?php

namespace App\Controller\Admin\Crud;

use App\Classes\Enum\EnumUserRole;
use App\Controller\Admin\Common\BaseCrudController;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends BaseCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('username')->setRequired(true);
        yield EmailField::new('email')->setRequired(true);
        yield ChoiceField::new('roles')->setChoices(EnumUserRole::getList())->allowMultipleChoices()->setRequired(false);
    }
}
