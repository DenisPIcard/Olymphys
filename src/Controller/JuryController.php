<?php
// src/Controller/CoreController.php
namespace App\Controller;

use App\Form\NotesType ;
use App\Form\PhrasesType ;
use App\Form\EquipesType ;
use App\Form\JuresType ;

use App\Entity\Jures ;
use App\Entity\Equipes ;
use App\Entity\Totalequipes ;
use App\Entity\Notes ;
use App\Entity\Visites ;
use App\Entity\Phrases ;

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
                $repositoryNotes = $this->getDoctrine()
		->getManager()
		->getRepository('App:Notes')
		;

		$listEquipes=array();
                $progression=array();
                
 		foreach ($attrib as $key => $value) 
		{
			$equipe=$repositoryEquipes->findOneByLettre($key);
			$listEquipes[$key] = $equipe;
			$id = $equipe->getId();
                        $note=$repositoryNotes->EquipeDejaNotee($id_jure ,$id);
			$progression[$key] = (!is_null($note)) ? 1 : 0 ;

		}
                
                $content = $this->get('templating')->render('cyberjury/accueil.html.twig', 
			array('listEquipes' => $listEquipes,'progression'=>$progression,'jure'=>$jure)
			);
                
  
                
		return new Response($content);

   
        }
        
        /**
	* @Security("has_role('ROLE_JURY')")
	*/
	public function infos_equipe(Request $request, Equipes $equipe, $id)
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

		$note=$repositoryNotes = $this->getDoctrine()
		->getManager()
		->getRepository('App:Notes')
		->EquipeDejaNotee($id_jure,$id)
		;
		$progression = (!is_null($note)) ? 1 : 0 ;

		$repositoryEquipes = $this
		->getDoctrine()
		->getManager()
		->getRepository('App:Equipes');

		//$equipe=$repositoryEquipes->find($id);
		$lettre=$equipe->getLettre();

		$repositoryTotEquipes = $this
		->getDoctrine()
		->getManager()
		->getRepository('App:Totalequipes');

		$equipe=$repositoryTotEquipes->findOneByLettreEquipe($lettre);
		$eq=$repositoryEquipes->findOneByLettre($lettre);

		$repositoryEleves = $this
		->getDoctrine()
		->getManager()
		->getRepository('App:Eleves');

		$listEleves=$repositoryEleves->findByLettreEquipe($lettre);

		$content = $this->get('templating')->render('cyberjury/infos.html.twig',
			array(
				'equipe'=>$equipe, 
				'eq'=>$eq,
				'listEleves'=>$listEleves, 
				'id_equipe'=>$id,
				'progression'=>$progression,
				'jure'=>$jure
				)
			);
		return new Response($content);   
        }
        
        /**
	* @Security("has_role('ROLE_JURY')")
	*/
  	public function evaluer_une_equipe(Request $request, Equipes $equipe, $id)
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

		$repositoryEquipes = $this->getDoctrine()
		->getManager()
		->getRepository('App:Equipes');
		//$equipe = $repositoryEquipes->find($id);
		$lettre=$equipe->getLettre();

		$repositoryJures = $this->getDoctrine()
		->getManager()
		->getRepository('App:Jures');
		$jure = $repositoryJures->find($id_jure);
		$attrib = $jure->getAttributions();   
		
		$em=$this->getDoctrine()->getManager();

		// Création de l'entité Notes
		$notes = $repositoryNotes = $this->getDoctrine()
		->getManager()
		->getRepository('App:Notes')
		->EquipeDejaNotee($id_jure, $id);

		$flag=0; 

		if(is_null($notes))
		{	
			$notes = new Notes();			
			$notes->setEquipe($equipe);
			$notes->setJure($jure);
			$progression = 0; 

			if($attrib[$lettre]==1)
			{	
       			$form = $this->createForm(NotesType::class, $notes, array('EST_PasEncoreNotee'=> true, 'EST_Lecteur'=> true,));
				$flag=1;
			}
			else
			{
				$notes->setEcrit(0);
				// On crée le Formulaire grâce au service form factory.
       			$form = $this->createForm(NotesType::class, $notes, array('EST_PasEncoreNotee'=> true, 'EST_Lecteur'=> false,));
			}
		}
		else
		{
			$notes=$this->getDoctrine()
			->getManager()
			->getRepository('App:Notes')
			->EquipeDejaNotee($id_jure,$id); 
			$progression = 1; 

			if($attrib[$lettre]==1)
			{
       			$form = $this->createForm(NotesType::class, $notes, array('EST_PasEncoreNotee'=> false, 'EST_Lecteur'=> true,));
				$flag=1;
			}
			else
			{
			$notes->setEcrit('0');
       		$form = $this->createForm(NotesType::class, $notes, array('EST_PasEncoreNotee'=> false, 'EST_Lecteur'=> false,));
			}
		}

		// Reste de la méthode
		// Si la requête est en post, c'est que le visiteur a soumis le formulaire. 
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			// création et gestion du formulaire. 

			$em->persist($notes);
			$em->flush();
			$request -> getSession()->getFlashBag()->add('notice', 'Notes bien enregistrées');
			// puis on redirige vers la page de visualisation de cette note dans le tableau de bord
			return $this->redirectToroute('cyberjury_tableau_de_bord');
		}
		// Si on n'est pas en POST, alors on affiche le formulaire. 

		$content = $this->get('templating')->render('cyberjury/evaluer.html.twig', 
			array(
				'equipe'=>$equipe,
				'form'=>$form->createView(),
				'flag'=>$flag,
				'progression'=>$progression,
				'jure'=>$jure
				  ));
		return new Response($content);
		
	}
      
        /**
	* @Security("has_role('ROLE_JURY')")
	*/
	public function tableau(Request $request)
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

		$repository = $this->getDoctrine()
		->getManager()
		->getRepository('App:Notes')
		;
		$em=$this->getDoctrine()->getManager();
		// Création de l'entité Notes
//		$notes = new Notes();
		$MonClassement = $repository->MonClassement($id_jure);
		
		$repository = $this->getDoctrine()
		->getManager()
		->getRepository('App:Equipes')
		;
		$em=$this->getDoctrine()->getManager();

		$listEquipes = array();
		$j=1;
		foreach($MonClassement as $notes)
		{
			$id = $notes->getEquipe();
			$equipe = $repository->find($id);
			$listEquipes[$j]['id']= $equipe->getId();
			$listEquipes[$j]['lettre']=$equipe->getLettre();
			$listEquipes[$j]['titre']=$equipe->getTitreProjet();
			$listEquipes[$j]['exper']=$notes->getExper();
			$listEquipes[$j]['demarche']=$notes->getDemarche();
			$listEquipes[$j]['oral']=$notes->getOral();
			$listEquipes[$j]['origin']=$notes->getOrigin();
			$listEquipes[$j]['wgroupe']=$notes->getWgroupe();
			$listEquipes[$j]['ecrit']=$notes->getEcrit();
			$listEquipes[$j]['points']=$notes->getPoints();
			$listEquipes[$j]['urlMemoire']=$equipe->getUrlMemoire();
			$j++;
		}

		$content = $this->get('templating')->render('cyberjury/tableau.html.twig', 
			array('listEquipes'=>$listEquipes,'jure'=>$jure)
			);
		return new Response($content);
	}
        
        /**
	* @Security("has_role('ROLE_JURY')")
	*/
	public function phrases(Request $request, Equipes $equipe, $id)
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

		$notes = $repositoryNotes = $this->getDoctrine()
		->getManager()
		->getRepository('App:Notes')
		->EquipeDejaNotee($id_jure, $id);
		$progression = (!is_null($notes)) ? 1 : 0 ;

		// Création de l'entité Phrases
		$repositoryPhrases = $this->getDoctrine()
		->getManager()
		->getRepository('App:Phrases');

		$repositoryLiaison = $this->getDoctrine()
		->getManager()
		->getRepository('App:Liaison');

		$repositoryEquipes = $this
		->getDoctrine()
		->getManager()
		->getRepository('App:Equipes');

		//$equipe=$repositoryEquipes->find($id);

		$em=$this->getDoctrine()->getManager();

		$form = $this->createForm(EquipesType::class, $equipe, array('Attrib_Phrases'=> true, 'Attrib_Cadeaux'=> false));
	
		// Si la requête est en post, c'est que le visiteur a soumis le formulaire. 
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			// création et gestion du formulaire. 
			
			$em->persist($equipe);
			$em->flush();
			$request -> getSession()->getFlashBag()->add('notice', 'Phrase et prix amusants bien enregistrés');
			
			return $this->redirectToroute('cyberjury_accueil');
		}
		// Si on n'est pas en POST, alors on affiche le formulaire. 

		$content = $this->get('templating')->render('cyberjury\phrases.html.twig', 
			array(
				'equipe'=>$equipe,
				'form'=>$form->createView(),
				'progression'=>$progression,
				'jure'=>$jure
				  ));
		return new Response($content);
        }    
}
