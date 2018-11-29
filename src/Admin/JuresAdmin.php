<?php
// src/Admin/JuresAdmin.php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class JuresAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('prenomJure', TextType::class)
                   ->add('nomJure', TextType::class)
                   ->add('initialesJure', TextType::class)
                   ->add('A', NumberType::class)
                   ->add('B', NumberType::class)
                   ->add('C', NumberType::class)
                   ->add('D', NumberType::class)
                   ->add('E', NumberType::class)
                   ->add('F', NumberType::class)
                   ->add('G', NumberType::class)
                   ->add('H', NumberType::class)
                   ->add('I', NumberType::class)
                   ->add('J', NumberType::class)
                   ->add('K', NumberType::class)
                   ->add('L', NumberType::class)
                   ->add('M', NumberType::class)
                   ->add('N', NumberType::class)
                   ->add('O', NumberType::class)
                   ->add('P', NumberType::class)
                   ->add('Q', NumberType::class)
                   ->add('R', NumberType::class)
                   ->add('S', NumberType::class)
                   ->add('T', NumberType::class)
                   ->add('U', NumberType::class)
                   ->add('V', NumberType::class)
                   ->add('W', NumberType::class)
                   ->add('X', NumberType::class)
                   ->add('Y', NumberType::class)
                   ->add('Z', NumberType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('nomJure');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('prenomJure')
                   ->addIdentifier('nomJure')
                   ->add('initialesJure')
                   ->add('A')
                   ->add('B')
                   ->add('C')
                   ->add('D')
                   ->add('E')
                   ->add('F')
                   ->add('G')
                   ->add('H')
                   ->add('I')
                   ->add('J')
                   ->add('K')
                   ->add('L')
                   ->add('M')
                   ->add('N')
                   ->add('O')
                   ->add('P')
                   ->add('Q')
                   ->add('R')
                   ->add('S')
                   ->add('T')
                   ->add('U')
                   ->add('V')
                   ->add('W')
                   ->add('X')
                   ->add('Y')
                   ->add('Z');
    }
}
