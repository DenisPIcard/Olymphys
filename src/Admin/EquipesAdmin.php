<?php
// src/Admin/EquipesAdmin.php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EquipesAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper ->add('lettre', TextType::class)
                    ->add('titreProjet', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('lettre')
                       ->add('titreProjet');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('lettre')
                   ->addIdentifier('titreProjet');
    }
}

