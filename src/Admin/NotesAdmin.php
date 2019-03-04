<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use App\Entity\Jures ;
use App\Entity\Equipes;

class NotesAdmin extends AbstractAdmin
{
    
    protected $baseRoutePattern = 'notes';
    protected $baseRouteName = 'sonata_notes';
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->with('Notes', ['class' => 'col-md-6'])
                   ->add('equipe.lettre', TextType::class)
                   ->add('jure.nomJure', TextType::class)
                   ->add('exper', IntegerType::class)
                   ->add('demarche', IntegerType::class)
                   ->add('oral', IntegerType::class)
                   ->add('origin', IntegerType::class)
                   ->add('Wgroupe', IntegerType::class)
                   ->add('ecrit', IntegerType::class)
                ->end()
                
                ;
    }
 
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('equipe.lettre')
                       ->add('jure.nomJure')
   
                  ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('equipe', null, ['associated_property' => 'lettre'])
                   ->addIdentifier('jure',null, ['associated_property' => 'nomjure'])
                   ->add('exper', null, ['row_align'=>'center'])
                   ->add('demarche', null, ['row_align'=>'center'])
                   ->add('oral', null, ['row_align'=>'center'])
                   ->add('origin', null, ['row_align'=>'center'])
                   ->add('Wgroupe', null, ['row_align'=>'center'])
                   ->add('ecrit', null, ['row_align'=>'center'])
                ;
    }

}

