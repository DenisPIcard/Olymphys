<?php
// src/Admin/ElevesAdmin.php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ElevesAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('nom', TextType::class)
                   ->add('prenom', TextType::class)
                   ->add('numero_equipe', TextType::class)
                   ->add('lettre_national', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('nom')
                       ->add('numero_equipe')
                       ->add('lettre_national');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('nom')
                   ->addIdentifier('prenom')
                   ->addIdentifier('numero_equipe')
                   ->addIdentifier('lettre_national');
    }
}
