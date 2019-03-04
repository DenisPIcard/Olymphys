<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sonata\AdminBundle\Form\Type\ModelType;
use App\Entity\Classement;

class PrixAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('ordre', IntegerType::class)
                   ->add('prix',TextType::class)
                   ->add('classement', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('ordre')
                       ->add('prix')
                       ->add('classement');
                       
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('ordre')
                    ->addIdentifier('classement')
                   ->addIdentifier('prix')
                   ->add('attribue');
    }
}
