<?php

namespace App\Controller\Admin;

use App\Entity\Localisation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LocalisationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Localisation::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
