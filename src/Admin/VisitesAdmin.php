<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VisitesAdmin extends AbstractAdmin
{
            
    protected $baseRoutePattern = 'visites';
    protected $baseRouteName = 'sonata_visites';
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('intitule', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('intitule');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('intitule')
                   ;
    }
}
