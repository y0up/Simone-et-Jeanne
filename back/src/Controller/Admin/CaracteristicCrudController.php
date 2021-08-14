<?php

namespace App\Controller\Admin;

use App\Entity\Caracteristic;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CaracteristicCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Caracteristic::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            AssociationField::new('caracteristicDetail'),
            TextField::new('content'),
            DateField::new('createdAt')->hideOnForm(),
        ];
    }
}
