<?php

namespace App\Controller\Admin;

use App\Entity\OrderDetail;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderDetailCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderDetail::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('commandNumber')->hideOnForm(),
            IntegerField::new('total')->hideOnForm(),
            TextField::new('shippingChoice')->hideOnForm(),
            TextField::new('status'),
            DateField::new('createdAt')->hideOnForm(),
            
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add( Crud::PAGE_INDEX, 'detail');
    }
}
