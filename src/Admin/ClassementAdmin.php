<?php
// src/Admin/ClassementAdmin.php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ClassementAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper ->add('niveau', TextType::class)
                    ->add('montant', MoneyType::class, array('currency'=>'EUR'))
                    ->add('nbreprix', IntegerType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('niveau')
                       ->add('montant');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('niveau')
                   ->addIdentifier('montant')
                   ->add('nbreprix', null, ['row_align'=>'left']);
    }
}
