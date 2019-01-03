<?php

namespace App\Application\Sonata\UserBundle\Admin;

use Sonata\UserBundle\Admin\Model\UserAdmin as BaseUserAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends BaseUserAdmin
{
    protected function configureFormFields( FormMapper $formMapper ): void
       {
        parent::configureFormFields($formMapper);
        $formMapper
                ->tab('User')
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
                   # ->add('rne')
                ;
        }
}

