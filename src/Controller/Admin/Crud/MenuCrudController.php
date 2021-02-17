<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Common\BaseCrudController;
use App\Entity\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Knp\Menu\FactoryInterface;

class MenuCrudController extends BaseCrudController
{
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createEntity(string $entityFqcn)
    {
        return new $entityFqcn(uniqid("menu_"), $this->factory);
    }

    public static function getEntityFqcn(): string
    {
        return MenuItem::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('Menu')
            ->setEntityLabelInPlural('Menus');
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('parent');
        yield TextField::new('name');
        yield TextField::new('label');

        yield FormField::addPanel("a")
            ->collapsible()
            ->renderCollapsed()
            ->setIcon('phone')->addCssClass('optional')
            ->setHelp('Phone number is preferred');

        yield UrlField::new('uri');
        yield ArrayField::new('attributes');
        yield ArrayField::new('linkAttributes');
        yield ArrayField::new('childrenAttributes');
        yield ArrayField::new('labelAttributes');
        yield FormField::addPanel("b")->collapsible();

//        yield ArrayField::new('extras');
        yield BooleanField::new('display');
        yield BooleanField::new('displayChildren');
        yield AssociationField::new('children')->hideOnForm();
    }

}
