<?php
// src/Admin/JuresAdmin.php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class JuresAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('prenomJure', TextType::class)
                   ->add('nomJure', TextType::class)
                   ->add('initialesJure', TextType::class)
                   ->add('A', IntegerType::class, ['required' => false])
                   ->add('B', IntegerType::class, ['required' => false])
                   ->add('C', IntegerType::class, ['required' => false])
                   ->add('D', IntegerType::class, ['required' => false])
                   ->add('E', IntegerType::class, ['required' => false])
                   ->add('F', IntegerType::class, ['required' => false])
                   ->add('G', IntegerType::class, ['required' => false])
                   ->add('H', IntegerType::class, ['required' => false])
                   ->add('I', IntegerType::class, ['required' => false])
                   ->add('J', IntegerType::class, ['required' => false])
                   ->add('K', IntegerType::class, ['required' => false])
                   ->add('L', IntegerType::class, ['required' => false])
                   ->add('M', IntegerType::class, ['required' => false])
                   ->add('N', IntegerType::class, ['required' => false])
                   ->add('O', IntegerType::class, ['required' => false])
                   ->add('P', IntegerType::class, ['required' => false])
                   ->add('Q', IntegerType::class, ['required' => false])
                   ->add('R', IntegerType::class, ['required' => false])
                   ->add('S', IntegerType::class, ['required' => false])
                   ->add('T', IntegerType::class, ['required' => false])
                   ->add('U', IntegerType::class, ['required' => false])
                   ->add('V', IntegerType::class, ['required' => false])
                   ->add('W', IntegerType::class, ['required' => false])
                   ->add('X', IntegerType::class, ['required' => false])
                   ->add('Y', IntegerType::class, ['required' => false])
                   ->add('Z', IntegerType::class, ['required' => false]);
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
