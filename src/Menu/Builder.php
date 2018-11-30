<?php
// src/Menu/Builder.php
namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Accueil', ['route' => 'core_home']);

        // access services from the container!
        $em = $this->container->get('doctrine')->getManager();

        // create another menu item
        $menu->addChild('Administration', ['route' => 'sonata_admin_redirect']);
        $menu->addChild('Pages comité', ['route' => '']);
        $menu->addChild('Accueil du Jury', ['route' => 'cyberjury_accueil']);
        $menu->addChild('Secrétariat du Jury', ['route' => 'secretariat_accueil']);
        $menu->addChild('Galeries photos', ['route' => '']);


        return $menu;
    }
}


