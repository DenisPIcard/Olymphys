<?php
// src/Admin/JuresAdmin.php

namespace App\Admin\Extensions;

use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminExtensionInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CadeauxExtension extends AbstractAdminExtension implements AdminExtensionInterface
{
   public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('contenu', TextType::class)
                   ->add('fournisseur', TextType::class)
                   ->add('montant', TextType::class);
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('fournisseur');
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('contenu')
                   ->addIdentifier('fournisseur')
                   ->add('montant');
    }

}
