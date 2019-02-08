<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CadeauxAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('contenu', TextType::class)
                   ->add('fournisseur', TextType::class)
                   ->add('montant', MoneyType::class, array('currency'=>'EUR'))
                   ->add('attribue', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('contenu')
                       ->add('fournisseur')
                       ->add('montant')
                       ->add('attribue');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('contenu')
                   ->addIdentifier('fournisseur')
                   ->add('montant')
                   ->add('attribue');
    }

    
}

