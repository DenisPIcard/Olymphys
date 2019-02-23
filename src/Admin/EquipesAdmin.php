<?php
// src/Admin/EquipesAdmin.php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use App\Entity\Classement;
use App\Entity\Prix;
use App\Entity\Visites;
use App\Entity\Cadeaux;

class EquipesAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper ->add('lettre', TextType::class)
                    ->add('titreProjet', TextType::class)
                    //->add('classement',EntityType::class, [
                    //    'class'=> Classement::class,
                     //   'choice_label'=> 'niveau',])
                    ->add('classement', TextType::class, ['required' => false])
                    ->add('prix', EntityType::class, [
                        'class'=> Prix::class, 
                        'choice_label'=> 'prix',])
                    ->add('visite',EntityType::class, [
                        'class' => Visites::class,
                        'choice_label' => 'intitule',])
                     ->add('cadeau',EntityType::class, [
                        'class' => Cadeaux::class,
                        'choice_label' => 'contenu',])
        ;
        

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('lettre')
                       ->add('titreProjet')
                       ->add('classement');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('lettre')
                   ->addIdentifier('titreProjet')
                   ->add('classement')
                   ->add('prix',null,['associated_property' => 'prix'])
                   ->add('visite',null, ['associated_property' =>'intitule'])
                   ->add('cadeau',null, ['associated_property' =>'contenu'])
                   ;
    }
}

