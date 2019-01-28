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
                ->add('lastname', null, ['label'=> 'Nom'])
                ->add('firstname', null, ['label'=> 'Prénom'])
                ->add('phone', null, ['required'=> false, 'label'=>'Téléphone',])
                ->add('rne', null, ['required'=> false, 'label'=>'RNE, si vous comptez inscrire une équipe'])
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


