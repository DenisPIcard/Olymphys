<?php
// src/Admin/JuresAdmin.php

namespace App\Admin\Extensions;

use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminExtensionInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class NotesExtension extends AbstractAdminExtension implements AdminExtensionInterface
{
    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('', TextType::class)
                   ->add('jure_id', TextType::class)
                   ->add('', TextType::class);
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('equipe_id')
                       ->add('jure_id');
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('equipe_id')
                   ->addIdentifier('jure_id')
                   ->add('exper');
    }
}
