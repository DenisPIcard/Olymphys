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
                ->add('lastname', null, ['label'=> 'Votre nom'])
                ->add('firstname', null, ['label'=> 'Votre prénom'])
                ->add('adresse', null, ['label'=>'Votre adresse (numéro +rue)'])
                ->add('ville', null, ['label'=>'Votre ville'])
                ->add('code', null, ['label'=>'Votre code'])
                ->add('phone', null, ['required'=> false, 'label'=>'Votre téléphone',])
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


