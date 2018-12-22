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
       $menu->setChildrenAttribute('class', 'nav flex-column nav-pills');
      
       
       $menu->addChild('Accueil du site', ['route' => 'core_home']);
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
    
    public function createUtilisateurMenu(array $options)
    {
        $menu = $this->factory->createItem('utilisateur');
    }
    
    public function createSecretariatMenu(array $options)
    {
        $menu = $this->factory->createItem('secretariat');

        if (isset($options['include_homepage']) && $options['include_homepage']) {
            $menu->addChild('Home', ['route' => 'homepage']);
        }
        $menu->setChildrenAttribute('class', 'nav flex-column nav-pills');
        $menu->addChild('Accueil du secrétariat', [ 'attributes' => ['dropdown' => true,],]);
        $menu['Accueil du secrétariat']
                    ->addChild('Accueil du secrétariat', ['route' => 'secretariat_accueil', 'attributes' => ['dropdown' => true,]]);
 
        $menu['Accueil du secrétariat']
                    ->addChild('Vue Globale', ['route' => 'secretariat_vueglobale', 'attributes' => ['dropdown' => true,]]);

        $menu->addChild('Le Classement des équipes');
            $menu['Le Classement des équipes']->addChild('Classement des équipes (total des points décroissant)',['route' =>'secretariat_classement']);
            $menu['Le Classement des équipes']->addChild('Les Prix',['route'=> 'secretariat_lesprix']);
            $menu['Le Classement des équipes']->addChild('Le palmarès (selon les points et les niveaux de prix)', ['route' =>'secretariat_palmares']);
            $menu['Le Classement des équipes']->addChild('Le palmarès ajusté', ['route' =>'secretariat_palmares_ajuste']);
            $menu['Le Classement des équipes']->addChild('Le palmarès définitif', ['route' =>'secretariat_palmares_definitif']);
        $menu->addChild('Les prix');
            $menu['Les prix']->addChild('Initialisation des tables', ['route'=>'secretariat_mise_a_zero']);
            $menu['Les prix']->addChild('Attribution des premiers prix', ['route'=>'secretariat_attrib_prix','routeParameters' => ['niveau' => 1 ]]);
            $menu['Les prix']->addChild('Attribution des deuxièmes prix', ['route'=>'secretariat_attrib_prix','routeParameters' => ['niveau' => 2 ]]);
            $menu['Les prix']->addChild('Attribution des troisièmes prix', ['route'=>'secretariat_attrib_prix','routeParameters' => ['niveau' => 3 ]]);
            $menu['Les prix']->addChild('Édition des prix', ['route'=>'secretariat_edition_prix']);
        $menu->addChild('Les récompenses');
            $menu['Les récompenses']->addChild('Édition des visites',['route'=>'secretariat_edition_visites']);
            $menu['Les récompenses']->addChild('Attribution des cadeaux', ['route'=>'secretariat_lescadeaux'] );
            $menu['Les récompenses']->addChild('Édition des cadeaux',['route'=>'secretariat_edition_cadeaux']);
            $menu['Les récompenses']->addChild('Édition des phrases et prix amusants',['route'=>'secretariat_edition_phrases']);
        $menu->addChild('Édition du palmarès');
            $menu['Édition du palmarès']->addChild('Édition du palmarès complet',['route'=>'secretariat_edition_palmares_complet']);
            $menu['Édition du palmarès']->addChild('Fichier Excel pour le site',['route'=>'secretariat_tableau_excel_palmares_site']);
            $menu['Édition du palmarès']->addChild('Fichier Excel pour la proclamation du palmarès',['route'=>'secretariat_tableau_excel_palmares_jury']);
        $menu->addChild('Déconnexion', ['route'=>'fos_user_security_logout'])
             ->setAttribute('class', 'fas fa-procedures');
        return $menu;
    }

}
