<?php

namespace App\Application\Sonata\UserBundle\Admin;

use Sonata\UserBundle\Admin\Model\UserAdmin as BaseUserAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserAdmin extends BaseUserAdmin
{
    protected function configureFormFields( FormMapper $formMapper ): void
       {
        parent::configureFormFields($formMapper);
        $formMapper
                ->tab('User') 
                    ->remove('dateOfBirth')
                    ->remove('website')
                    ->remove('biography')
                    ->remove('locale')
                    ->remove('timezone')
                    ->remove('facebookUid')
                    ->remove('facebookName')
                    ->remove('twitterUid')
                    ->remove('twitterName')
                    ->remove('gplusUid')
                    ->remove('gplusName')
                ->with('General')
                    ->add('adresse', TextType::class)
                    ->add('ville', TextType::class)
                    ->add('code', IntegerType::class)
                    ->add('rne', TextType::class, ['required' => false,'label'=>'RNE'])
                ->end()
                ;
        }
}

