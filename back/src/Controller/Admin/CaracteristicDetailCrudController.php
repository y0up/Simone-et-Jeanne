<?php

namespace App\Controller\Admin;

use App\Entity\CaracteristicDetail;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CaracteristicDetailCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CaracteristicDetail::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            AssociationField::new('caracteristic'),
            TextField::new('info'),
            DateField::new('createdAt')->hideOnForm(),
        ];
    }
}
