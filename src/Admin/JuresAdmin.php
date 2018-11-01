<?php
// src/Admin/JuresAdmin.php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class JuresAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('prenomJure', TextType::class)
                   ->add('nomJure', TextType::class)
                   ->add('initialesJure', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('nomJure');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('prenomJure')
                   ->addIdentifier('nomJure')
                   ->add('initialesJure');
    }
}
