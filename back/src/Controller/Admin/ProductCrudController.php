<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextareaField::new('description'),
            DateField::new('createdAt')->hideOnForm(),
            MoneyField::new('price')->setCurrency('EUR'),
            TextField::new('brand'),
            BooleanField::new('new'),
            CollectionField::new('images')
                ->setEntryType(ImageType::class)
                ->setFormTypeOption('by_reference', false)
                ->onlyOnForms(),
            CollectionField::new('images')
                ->setTemplatePath('admin/images.html.twig')
                ->onlyOnDetail(),
            AssociationField::new('categories'),
            AssociationField::new('caracteristic'),
            AssociationField::new('caracteristicDetails'),
            IntegerField::new('quantity'),

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add( Crud::PAGE_INDEX, 'detail');
    }
}
