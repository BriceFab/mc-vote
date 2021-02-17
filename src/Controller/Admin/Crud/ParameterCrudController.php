<?php

namespace App\Controller\Admin\Crud;

use App\Classes\Enum\EnumParamType;
use App\Controller\Admin\Common\BaseCrudController;
use App\Entity\Parameter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Component\Form\FormBuilderInterface;

class ParameterCrudController extends BaseCrudController
{
    public static function getEntityFqcn(): string
    {
        return Parameter::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('Paramètre')
            ->setEntityLabelInPlural('Paramètres');
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextareaField::new('value');
        yield TextareaField::new('description');
        yield DateField::new('expirationDate');
        yield DateTimeField::new('updatedAt')->hideOnForm();
        yield DateTimeField::new('createdAt')->hideOnForm();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return parent::configureFilters($filters)
            ->add("param_key")
            ->add("expirationDate");
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $fields = $entityDto->getFields();

        /** @var Parameter $entity */
        $entity = $entityDto->getInstance();
        if (!is_null($entity->getParamType())) {
            /** @var FieldDto $paramType */
            switch ($entity->getParamType()) {
                case EnumParamType::IMAGE:
                    $fields['file'] = (ImageField::new('file'))->setFieldFqcn(ImageField::class)->getAsDto();
                    unset($fields['value']);
                    break;
                case EnumParamType::BOOLEAN:
                    dd("TODO", $fields, $entityDto, $formOptions);
                    $fields['01EYS7EZYYCMXBXWJ83GVVPSJD'] = (BooleanField::new('setFormattedValue'))->setRequired(false)->setFieldFqcn(BooleanField::class)->getAsDto();
                    unset($fields['01EYS7K3VPHM6NDRNCP1BG0Z6Y']);
                    break;
            }
        }

        $entityDto->setFields($fields);

        return parent::createEditFormBuilder($entityDto, $formOptions, $context);
    }

}
