<?php
// src/Controller/CoreController.php
namespace App\Controller;

use App\Entity\Jures ;
use App\Entity\Equipes ;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextaeraType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller ;
use Symfony\Component\HttpFoundation\Request ; # récupérer des arguments de la requête hors route, récupérer la méthode de la requête HTTP. 
use Symfony\Component\HttpFoundation\RedirectResponse ;
use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class JuryController extends Controller
{

	public function accueil()
 
        {
            	$user=$this->getUser();
		$nom=$user->getUsername();

		$repositoryJure = $this
			->getDoctrine()
			->getManager()
			->getRepository('App:Jures')
			;

		$jure=$repositoryJure->findOneByNomJure($nom);
		$id_jure = $jure->getId();
                
 		$attrib = $jure->getAttributions();
                
		$repositoryEquipes = $this
			->getDoctrine()
			->getManager()
			->getRepository('App:Equipes')
			;
		$listEquipes=array();
                
 		foreach ($attrib as $key => $value) 
		{
			$equipe=$repositoryEquipes->findOneByLettre($key);
			$listEquipes[$key] = $equipe;
			$id = $equipe->getId();

		}
                
                $content = $this->get('templating')->render('cyberjury/accueil.html.twig', 
			array('listEquipes' => $listEquipes,'jure'=>$jure)
			);
                
  
                
		return new Response($content);
        return $this->render('cyberjury/accueil.html.twig');
   
        }


    
  }

