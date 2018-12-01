<?php
// src/Menu/MenuBuilder.php
namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MenuBuilder implements ContainerAwareInterface
{
     use ContainerAwareTrait;
     
      /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authorizationChecker)
    {
      $this->factory = $factory;
      $this->checker = $authorizationChecker;
    }    
    
    public function createMainMenu(array $options)
    {
        
      
       $menu = $this->factory->createItem('root');
       $menu->setChildrenAttribute('class', 'nav nav-tabs nav-stacked');
        
       $menu->addChild('Accueil', ['route' => 'core_home']);
        // create another menu item
       if($this->checker->isGranted('ROLE_ADMIN'))
       {
            $menu->addChild('Administration');
            $menu['Administration']->addChild('Tableau de bord', ['route' => 'sonata_admin_redirect']);
            $menu['Administration']->addChild('Secrétariat du Jury', ['route' => 'secretariat_accueil']);
       }
       if($this->checker->isGranted('ROLE_COMITE'))
       {
           $menu->addChild('Pages comité', ['route' => '']);
       }
       if($this->checker->isGranted('ROLE_JURY'))
       {
            $menu->addChild('Accueil du Jury', ['route' => 'cyberjury_accueil']);
       }

       $menu->addChild('Galeries photos', ['route' => '']);
       $menu->addChild('Mémoires', ['route' => '']);
       $menu->addChild('Présentations', ['route' => '']);

        return $menu;
    }
}


