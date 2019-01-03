<?php
//src/Application/FOSUser/Form

namespace App\Application\FOSUser\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{
    protected $translationDomain = 'App/translations'; //
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('lastname')
                ->add('firstname')
                ->add('phone')
         ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }
    
    public function getName()
    {
        return 'app_user_profile';
    }
    
    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}


