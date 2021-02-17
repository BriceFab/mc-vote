<?php

namespace App\Controller\Admin\Common;

use App\Classes\Enum\EnumParamKey;
use App\Helpers\ParameterHelper;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

abstract class BaseCrudController extends AbstractCrudController
{

    public function configureActions(Actions $actions): Actions
    {
        $paramHelper = ParameterHelper::getInstance($this->getDoctrine());

        $actionIcon = $paramHelper->getDatabaseParam(EnumParamKey::ADMIN_ACTION_ICON);

        return parent::configureActions($actions)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) use ($actionIcon) {
                return $action->setIcon(null)->setLabel('admin.action.ajouter');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) use ($actionIcon) {
                return $action->setIcon($actionIcon ? 'fa fa-pencil' : null)->setLabel($actionIcon ? false : 'admin.action.modifier');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) use ($actionIcon) {
                return $action->setIcon($actionIcon ? 'fa fa-trash' : null)->setLabel($actionIcon ? false : 'admin.action.supprimer');
            })
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) use ($actionIcon) {
                return $action->setIcon($actionIcon ? 'fa fa-search' : null)->setLabel($actionIcon ? false : 'admin.action.detail');
            });
    }

}