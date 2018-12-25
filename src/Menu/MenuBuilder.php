<?php
// src/Menu/MenuBuilder.php
namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\HttpFoundation\RedirectResponse ;
use Symfony\Component\HttpFoundation\Response ;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Event\ConfigureMenuEvent;

class MenuBuilder implements ContainerAwareInterface
{
     use ContainerAwareTrait;
     
      /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authorizationChecker)
    {
      $this->factory = $factory;
      $this->checker = $authorizationChecker;
    }    
    
    public function createMainMenu()
    {
    
       $menu = $this->factory->createItem('main');
       $menu->setChildrenAttribute('class', 'nav flex-column nav-pills');
       $menu->addChild('Accueil du site', ['route' => 'core_home']);

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
           if($this->checker->isGranted('ROLE_ADMIN')) 
           {}
           else {
           $menu->addChild('Accueil du Jury', ['route' => 'cyberjury_accueil']);
           }
       }

       $menu->addChild('Galeries photos', ['route' => '']);
       $menu->addChild('Mémoires', ['route' => '']);
       $menu->addChild('Présentations', ['route' => '']);
 

        return $menu;
    }
    
 /*   public function createUtilisateurMenu()
    {

        
        $menu = $this->factory->createItem('utilisateur');
        if (isset($options['include_homepage']) && $options['include_homepage']) {
            $menu->addChild('Home', ['route' => 'homepage']);
        }
        $menu->setChildrenAttribute('class', 'nav flex-column nav-pills'); 

            $menu->addChild('Utilisateur');
            //secho $nom		$user=$this->getUser();
	//	$nom=$user->getUsername();;
            $menu['Utilisateur']->addChild('Votre Profil', ['route'=>'fos_user_profile_show']);
            $menu['Utilisateur']->addChild('Déconnexion', ['route'=>'fos_user_security_logout']);

            
            $menu['Utilisateur']->addChild('Connectez vous, si vous avez un compte',['route'=>'fos_user_security_login']);
            $menu['Utilisateur']->addChild('créez un compte si vous en souhaitez un', ['route'=>'fos_user_registration_register']);
            $menu['Utilisateur']->addChild('Sinon, choisissez dans le menu');
        
       
        return $menu;       
    }
 */   
    public function createJuryMenu(array $options)
    {
        $menu = $this->factory->createItem('jury');

        if (isset($options['include_homepage']) && $options['include_homepage']) {
            $menu->addChild('Home', ['route' => 'homepage']);
        }
        $menu->setChildrenAttribute('class', 'nav flex-column nav-pills');
        $menu->addChild('Jury');
        $menu['Jury']->addChild('Déconnexion', ['route' =>'fos_user_security_logout','attributes'=>['class'=>'d-block fas fa-procedures']]);
        $menu['Jury']->addChild('Accueil du Jury', ['route' => 'cyberjury_accueil','attributes'=> ['class'=>'d-block fas fa-home']]); 
        $menu['Jury']->addChild('Tableau de bord', ['route' =>'cyberjury_tableau_de_bord', 'attributes'=> ['class'=>'d-block fas fa-list']]);
        $menu['Jury']->addChild('Les prix', ['route' =>'cyberjury_lesprix', 'attributes'=>['class'=>'d-block fas fa-list-alt']]);
        $menu['Jury']->addChild('Les cadeaux', ['route' =>'cyberjury_lescadeaux', 'attributes'=>['class'=>'d-block fas fa-gift']]);
       
      /*  if (null != $this->container->get('event_dispatcher'))
        {
        $this->container->get('event_dispatcher')->dispatch(
            ConfigureMenuEvent::CONFIGURE,
            new ConfigureMenuEvent($factory, $menu)
                );
        }
        */
        $menu['Jury']->addChild('Le palmarès', ['route' =>'cyberjury_palmares',  'attributes'=>['class'=>'d-block fas fa-leaf']]);
        return $menu;
    }
    
    public function createSecretariatMenu(array $options)
    {
        $menu = $this->factory->createItem('secretariat');

        if (isset($options['include_homepage']) && $options['include_homepage']) {
            $menu->addChild('Home', ['route' => 'homepage']);
        }
        $menu->setChildrenAttribute('class', 'nav flex-column nav-pills');
        $menu->addChild('Accueil du secrétariat');
        $menu['Accueil du secrétariat']
                    ->addChild('Accueil du secrétariat', ['route' => 'secretariat_accueil']);
 
        $menu['Accueil du secrétariat']
                    ->addChild('Vue Globale', ['route' => 'secretariat_vueglobale']);

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
