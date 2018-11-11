<?php 

namespace App\Controller ;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType ; 

use Symfony\Component\Form\AbstractType;

use App\Form\NotesType ;
use App\Form\PhrasesType ;
use App\Form\EquipesType ;
use App\Form\JuresType ;
use App\Form\CadeauxType ;
use App\Form\ClassementType ;
use App\Form\PrixType ;

use App\Entity\Equipes ;
use App\Entity\Totalequipes ;
use App\Entity\Jures ;
use App\Entity\Notes ;
use App\Entity\Visites ;
use App\Entity\Phrases ;
use App\Entity\Classement ;
use App\Entity\Prix ;
use App\Entity\Cadeaux ;
use App\Entity\Liaison ;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextaeraType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller ;
use Symfony\Component\HttpFoundation\Request ; # récupérer des arguments de la requête hors route, récupérer la méthode de la requête HTTP. 
use Symfony\Component\HttpFoundation\RedirectResponse ;
use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\ClassesPHPExcelStyle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

    
class SecretariatController extends Controller 
{
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function accueil(Request $request)
	{

		$user=$this->getUser();
		$repositoryEquipes = $this
			->getDoctrine()
			->getManager()
			->getRepository('App:Equipes');

		$repositoryEleves = $this
			->getDoctrine()
			->getManager()
			->getRepository('App:Eleves');

		$repositoryTotEquipes = $this
			->getDoctrine()
			->getManager()
			->getRepository('App:Totalequipes');

		$em=$this->getDoctrine()->getManager();

		$listEquipes=$repositoryEquipes->findAll();

		foreach ($listEquipes as $equipe)
		{
			$lettre = $equipe->getLettre(); 
			$info=$repositoryTotEquipes->findOneByLettreEquipe($lettre);

			$equipe->setInfoequipe($info);
			$em->persist($equipe);
			$em->flush();
		}

		$listEleves=$repositoryEleves->findAll();

		foreach ($listEleves as $eleve) 
		{ 
			$lettre = $eleve->getLettreEquipe(); 
			$equipe=$repositoryEquipes->findOneByLettre($lettre);

			$eleve->setEquipeleves($equipe);
			$em->persist($eleve);
			$em->flush();
		}

/*		$listEquipes = $this->getDoctrine()
			->getManager()
			->getRepository('App:Equipes')
			->getEquipesAccueil();
*/
		foreach ($listEquipes as $equipe) 
		{
			$lettre=$equipe->getLettre();
			$lesEleves[$lettre] = $repositoryEleves->findByLettreEquipe($lettre);
		}

		$content = $this->get('templating')->render('secretariat/accueil.html.twig', 
			array('listEquipes' => $listEquipes,
				  'lesEleves'=>$lesEleves));

		return new Response($content);
	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function vueglobale(Request $request)
	{
		$user=$this->getUser();
		$repositoryNotes = $this
		->getDoctrine()
		->getManager()
		->getRepository('App:Notes')
		;

		$repositoryJures = $this
		->getDoctrine()
		->getManager()
		->getRepository('App:Jures')
		;
		$listJures = $repositoryJures->findAll();

		$em=$this->getDoctrine()->getManager();
		
		$repositoryEquipes = $this
		->getDoctrine()
		->getManager()
		->getRepository('App:Equipes')
		;
		$listEquipes = $repositoryEquipes->findAll();

		$nbre_equipes = 0; 
		foreach ($listEquipes as $equipe)
		{
			$nbre_equipes = $nbre_equipes + 1 ; 
			$id_equipe = $equipe->getId(); 
			$lettre = $equipe->getLettre(); 

			$nbre_jures=0; 
			foreach ($listJures as $jure) 
			{	
				$id_jure = $jure->getId();
				$nbre_jures=$nbre_jures+1; 	

				$method = 'get'.ucfirst($lettre); 
				$statut = $jure->$method();
			
				if(is_null($statut))
				{
					$progression[$nbre_equipes][$nbre_jures] = 'ras' ;

				}
				elseif ($statut==1) 
				{
			        $note = $repositoryNotes->EquipeDejaNotee($id_jure, $id_equipe);
					$progression[$nbre_equipes][$nbre_jures] = (is_null($note)) ? 'zero' : $note->getSousTotal() ;
				}
				else
				{
			        $note = $repositoryNotes->EquipeDejaNotee($id_jure, $id_equipe);
					$progression[$nbre_equipes][$nbre_jures] = (is_null($note)) ? 'zero' : $note->getPoints() ;
				}	
			}
	    }

		$content = $this->get('templating')->render('secretariat/vueglobale.html.twig', array(
			'listJures'=>$listJures, 
			'listEquipes'=>$listEquipes,
			'progression'=>$progression, 
			'nbre_equipes'=>$nbre_equipes, 
			'nbre_jures'=>$nbre_jures,
			));
		return new Response($content);
	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/	
	public function classement(Request $request)
	{
		$user=$this->getUser();

		$repositoryEquipes = $this->getDoctrine()
		->getManager()
		->getRepository('App:Equipes');

		$repositoryNotes = $this->getDoctrine()
		->getManager()
		->getRepository('App:Notes');

		$em=$this->getDoctrine()->getManager();
		$listEquipes = $repositoryEquipes->findAll();

		foreach ($listEquipes as $equipe)
		{
			$listesNotes=$equipe->getNotess();
			$nbre_notes = $equipe->getNbNotes(); 

			$nbre_notes_ecrit=0; 			
			$points_ecrit = 0 ; 		
			$points = 0 ; 
			
			
			if ($nbre_notes==0) 
				{
					$equipe->setTotal(0);
					$em->persist($equipe);
					$em->flush();
				}	
			else
			{
				foreach ($listesNotes as $note) 
				{
					$points = $points + $note->getPoints(); 
					
					$nbre_notes_ecrit = ($note->getEcrit()) ? $nbre_notes_ecrit +1 : $nbre_notes_ecrit ; 
					$points_ecrit = $points_ecrit + $note->getEcrit()*5; 
				}
				$moyenne_oral = $points/$nbre_notes; 
				$moyenne_ecrit = ($nbre_notes_ecrit) ? $points_ecrit/$nbre_notes_ecrit : 0 ;

				$total =  $moyenne_oral + $moyenne_ecrit ; 
				$equipe->setTotal($total);
				$em->persist($equipe);
				$em->flush();
			}
		}

		/* $qb = $repositoryEquipes->createQueryBuilder('e');
		 $qb ->select('COUNT(e)') ;
		 $nbre_equipes = $qb->getQuery()->getSingleScalarResult(); 
                */
		$qb = $repositoryEquipes->createQueryBuilder('e');
                $qb ->orderBy('e.total', 'DESC');
                $classement = $qb ->getQuery()->getResult();
		// $classement = $repositoryEquipes->classement(0,0, $nbre_equipes); #->orderBy('e.total', 'DESC')	 entre 0 et $nbre_equipes;

		$rang=0; 
		
		foreach ($classement as $equipe) 
		{
			$rang = $rang + 1 ; 
			$equipe->setRang($rang);
			$em->persist($equipe);
			$em->flush();
		}

		// Le render ne change pas, on passait un tableau, maintenant un objet 
		$content = $this->get('templating')->render('secretariat/classement.html.twig', 
			//array('listEquipes'=>$listEquipes)
			array('classement' => $classement )
			);
		return new Response($content);
	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function lesprix(Request $request)
	{
		$user=$this->getUser();
		$repositoryPrix = $this->getDoctrine()
		->getManager()
		->getRepository('App:Prix');
		
		$ListPremPrix = $repositoryPrix->findByClassement('1er');
		$ListDeuxPrix = $repositoryPrix->findByClassement('2ème');
		$ListTroisPrix = $repositoryPrix->findByClassement('3ème');

		$content = $this->get('templating')->render('secretariat/lesprix.html.twig',
			array('ListPremPrix' => $ListPremPrix, 
                              'ListDeuxPrix' => $ListDeuxPrix, 
                              'ListTroisPrix' => $ListTroisPrix)
			);
		return new Response($content);
	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function modifier_prixAction(Request $request, $id_prix)
	{
		$user=$this->getUser();
		$repositoryPrix = $this
			->getDoctrine()
			->getManager()
			->getRepository('App:Prix');

		$repositoryClassement = $this->getDoctrine()
		->getManager()
		->getRepository('App:Classement');

		$prix = $repositoryPrix->find($id_prix); 
		$em=$this->getDoctrine()->getManager();

		$form = $this->createForm(PrixType::class, $prix);
				
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid() ) 
		{
			// création et gestion du formulaire. 
			$em->persist($prix);			
			$em->flush();

			$classement = $repositoryClassement->findOneByNiveau('1er');
			$nbrePremPrix = $repositoryPrix->getNbrePrix('1er');
			$classement->setNbreprix($nbrePremPrix); 
			$em->persist($classement);			
			$em->flush();

			$classement = $repositoryClassement->findOneByNiveau('2ème');			
			$nbreDeuxPrix = $repositoryPrix->getNbrePrix('2ème');
			$classement->setNbreprix($nbreDeuxPrix); 
			$em->persist($classement);			
			$em->flush();


			$classement = $repositoryClassement->findOneByNiveau('3ème');			
			$nbreTroisPrix = $repositoryPrix->getNbrePrix('3ème');
			$classement->setNbreprix($nbreTroisPrix); 
			$em->persist($classement);			
			$em->flush();


			$request -> getSession()->getFlashBag()->add('notice', 'Modifications bien enregistrées');
			// puis on redirige vers la page de visualisation de cette note dans le tableau de bord
			return $this->redirectToroute('secretariat_lesprix');

		}
		// Si on n'est pas en POST, alors on affiche le formulaire. 
		$content = $this->get('templating')->render('secretariat/modifier_prix.html.twig', 
			array(
				'prix'=>$prix,
				'form'=>$form->createView(),
				  ));
		return new Response($content);
	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function palmares(Request $request)
	{

		//$user=$this->getUser();
		$repositoryEquipes = $this->getDoctrine()
		->getManager()
		->getRepository('App:Equipes');
		//$listEquipes = $repositoryEquipes->findAll();


		$qb = $repositoryEquipes->createQueryBuilder('e');
		$qb ->select('COUNT(e)') ;
		$nbre_equipes = $qb->getQuery()->getSingleScalarResult(); 

		$repositoryClassement = $this->getDoctrine()
		->getManager()
		->getRepository('App:Classement');

		
		$NbrePremierPrix=$repositoryClassement
			->findOneByNiveau('1er')
			->getNbreprix();

		$NbreDeuxPrix = $repositoryClassement
			->findOneByNiveau('2ème')
			->getNbreprix(); 

		$NbreTroisPrix = $repositoryClassement
			->findOneByNiveau('3ème')
			->getNbreprix(); 
		
		//$ListPremPrix = $repositoryEquipes->classement(1,0, $NbrePremierPrix);  // par ordre decroissant du total 

                $qb = $repositoryEquipes->createQueryBuilder('e');
                $qb ->orderBy('e.total', 'DESC')
                    ->setFirstResult( 0 )
                    ->setMaxResults( $NbrePremierPrix );
                $ListPremPrix = $qb ->getQuery()->getResult();		

                // $offset = $NbrePremierPrix  ; 
		// $ListDeuxPrix = $repositoryEquipes->classement(2, $offset, $NbreDeuxPrix);
                $qb = $repositoryEquipes->createQueryBuilder('e');
                $qb ->orderBy('e.total', 'DESC')
                    ->setFirstResult($NbrePremierPrix )
                    ->setMaxResults($NbreDeuxPrix);		
                $ListDeuxPrix = $qb ->getQuery()->getResult();
                //$offset = $offset + $NbreDeuxPrix  ; 
		// $ListTroisPrix = $repositoryEquipes->classement(3, $offset, $NbreTroisPrix);
                $qb = $repositoryEquipes->createQueryBuilder('e');
                $qb ->orderBy('e.total', 'DESC')
                    ->setFirstResult($NbrePremierPrix+ $NbreDeuxPrix )
                    ->setMaxResults($NbreTroisPrix);	
                $ListTroisPrix = $qb ->getQuery()->getResult();
                
		$content = $this->get('templating')->render('secretariat/palmares.html.twig',
			array('ListPremPrix' => $ListPremPrix, 
			      'ListDeuxPrix' => $ListDeuxPrix,
			      'ListTroisPrix' => $ListTroisPrix,
			      'NbrePremierPrix' => $NbrePremierPrix, 
			      'NbreDeuxPrix' => $NbreDeuxPrix, 
			      'NbreTroisPrix' => $NbreTroisPrix)
			);
		return new Response($content);
	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function modifier_rang(Request $request, $id_equipe)
	{
		//$user=$this->getUser();
		$repositoryEquipes = $this
			->getDoctrine()
			->getManager()
			->getRepository('App:Equipes')
			;
		$equipe = $repositoryEquipes->find($id_equipe); 
		$em=$this->getDoctrine()->getManager();

		$form = $this->createForm(EquipesType::class, $equipe, 
			array(
				'Modifier_Rang'=>true,
				'Attrib_Phrases'=> false, 
				'Attrib_Cadeaux'=> false, 
				'Deja_Attrib'=>false,)
				);
		$ancien_rang = $equipe->getRang();		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid() ) 
                    {
                    $nouveau_rang = $equipe->getRang();
                    $max=0;
                    $mod=0;
                    if ($nouveau_rang < $ancien_rang)
                        {
                        $deb = $nouveau_rang-1;
                        $max = $ancien_rang-$nouveau_rang;
                        $mod = 1;
                        }
                    elseif($ancien_rang < $nouveau_rang)
                        {                            
                        $deb = $ancien_rang;
                        $max= $nouveau_rang-$deb;
                        $mod= -1;                                                      
                        }    

                    $qb = $repositoryEquipes->createQueryBuilder('e');
                    $qb ->orderBy('e.rang', 'ASC')
                        ->setFirstResult( $deb )
                         ->setMaxResults( $max );
                    $list = $qb ->getQuery()->getResult();

                    foreach($list as $eq)
                        {
                        $rang= $eq->getRang();
                        $eq ->setRang($rang+$mod);
                        $em->persist($eq);			                       
                        }
                    $em->persist($equipe);			
                    $em->flush();
                    $request -> getSession()->getFlashBag()->add('notice', 'Modifications bien enregistrées');
			// puis on redirige vers la page de visualisation de cette note dans le tableau de bord
                    return $this->redirectToroute('secretariat_palmares_ajuste');

                    }
		// Si on n'est pas en POST, alors on affiche le formulaire. 
		$content = $this->get('templating')->render('secretariat/modifier_rang.html.twig', 
			array(
				'equipe'=>$equipe,
				'form'=>$form->createView(),
				  ));
		return new Response($content);
	}

	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function palmares_ajuste(Request $request)
	{

		$user=$this->getUser();
		$repositoryEquipes = $this->getDoctrine()
		->getManager()
		->getRepository('App:Equipes');
		//$listEquipes = $repositoryEquipes->findAll();


		$qb = $repositoryEquipes->createQueryBuilder('e');
		$qb ->select('COUNT(e)') ;
		$nbre_equipes = $qb->getQuery()->getSingleScalarResult(); 

		$repositoryClassement = $this->getDoctrine()
		->getManager()
		->getRepository('App:Classement');

		
		$NbrePremierPrix=$repositoryClassement
			->findOneByNiveau('1er')
			->getNbreprix(); 

		$NbreDeuxPrix = $repositoryClassement
			->findOneByNiveau('2ème')
			->getNbreprix(); 

		$NbreTroisPrix = $repositoryClassement
			->findOneByNiveau('3ème')
			->getNbreprix(); 
		
		// $ListPremPrix = $repositoryEquipes->palmares(1,0, $NbrePremierPrix); // classement par rang croissant 
		// $offset = $NbrePremierPrix  ; 
                
                $qb = $repositoryEquipes->createQueryBuilder('e');
                $qb ->orderBy('e.rang', 'ASC')
                    ->setFirstResult( 0 )
                    ->setMaxResults( $NbrePremierPrix );
                $ListPremPrix = $qb ->getQuery()->getResult();		
                
		// $ListDeuxPrix = $repositoryEquipes->palmares(2, $offset, $NbreDeuxPrix);
		// $offset = $offset + $NbreDeuxPrix  ; 

                $qb = $repositoryEquipes->createQueryBuilder('e');
                $qb ->orderBy('e.rang', 'ASC')
                    ->setFirstResult( $NbrePremierPrix )
                    ->setMaxResults( $NbreDeuxPrix );
                $ListDeuxPrix = $qb ->getQuery()->getResult();		                
                
               // $ListTroisPrix = $repositoryEquipes->palmares(3, $offset, $NbreTroisPrix);

                $qb = $repositoryEquipes->createQueryBuilder('e');
                $qb ->orderBy('e.rang', 'ASC')
                    ->setFirstResult(  $NbrePremierPrix + $NbreDeuxPrix )
                    ->setMaxResults( $NbreTroisPrix );
                $ListTroisPrix = $qb ->getQuery()->getResult();		

		$content = $this->get('templating')->render('secretariat/palmares_ajuste.html.twig',
			array('ListPremPrix' => $ListPremPrix, 
			      'ListDeuxPrix' => $ListDeuxPrix,
			      'ListTroisPrix' => $ListTroisPrix,
			      'NbrePremierPrix' => $NbrePremierPrix, 
			      'NbreDeuxPrix' => $NbreDeuxPrix, 
			      'NbreTroisPrix' => $NbreTroisPrix)
			);
		return new Response($content);
	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function palmares_definitif(Request $request)
	{
		$user=$this->getUser();
		$repositoryEquipes = $this->getDoctrine()
		->getManager()
		->getRepository('App:Equipes');
		$em=$this->getDoctrine()->getManager();

		$repositoryClassement = $this->getDoctrine()
		->getManager()
		->getRepository('App:Classement');
		
		$NbrePremierPrix=$repositoryClassement
			->findOneByNiveau('1er')
			->getNbreprix(); 

		$NbreDeuxPrix = $repositoryClassement
			->findOneByNiveau('2ème')
			->getNbreprix(); 

		$NbreTroisPrix = $repositoryClassement
			->findOneByNiveau('3ème')
			->getNbreprix(); 

                $qb = $repositoryEquipes->createQueryBuilder('e');           
                $qb ->orderBy('e.total', 'DESC')
                    ->setFirstResult( 0 )
                    ->setMaxResults( $NbrePremierPrix );
                $ListPremPrix = $qb ->getQuery()->getResult();	
                
                $qb = $repositoryEquipes->createQueryBuilder('e');
                $qb ->orderBy('e.total', 'DESC')
                    ->setFirstResult($NbrePremierPrix )
                    ->setMaxResults($NbreDeuxPrix);		
                $ListDeuxPrix = $qb ->getQuery()->getResult();

                $qb = $repositoryEquipes->createQueryBuilder('e');
                $qb ->orderBy('e.total', 'DESC')
                    ->setFirstResult($NbrePremierPrix+ $NbreDeuxPrix )
                    ->setMaxResults($NbreTroisPrix);	
                $ListTroisPrix = $qb ->getQuery()->getResult();
                
		//$ListPremPrix = $repositoryEquipes->palmares(1,0, $NbrePremierPrix); // classement par rang croissant 
		//$offset = $NbrePremierPrix  ; 
		//$ListDeuxPrix = $repositoryEquipes->palmares(2, $offset, $NbreDeuxPrix);
		//$offset = $offset + $NbreDeuxPrix  ; 
		//$ListTroisPrix = $repositoryEquipes->palmares(3, $offset, $NbreTroisPrix);

		$rang=0; 

		foreach ($ListPremPrix as $equipe) 
		{
			$niveau = '1er'; 
			$equipe->setClassement($niveau);
			$rang = $rang + 1 ; 			
			$equipe->setRang($rang);
			$em->persist($equipe);
			$em->flush();
		}

		foreach ($ListDeuxPrix as $equipe) 
		{
			$niveau = '2ème';
			$equipe->setClassement($niveau);
			$rang = $rang + 1 ; 			
			$equipe->setRang($rang);
			$em->persist($equipe);
			$em->flush();
		}
		foreach ($ListTroisPrix as $equipe) 
		{
			$niveau = '3ème';
			$equipe->setClassement($niveau);
			$rang = $rang + 1 ; 			
			$equipe->setRang($rang);
			$em->persist($equipe);
			$em->flush();
		}

		$content = $this->get('templating')->render('secretariat/palmares_definitif.html.twig',
			array('ListPremPrix' => $ListPremPrix, 
			      'ListDeuxPrix' => $ListDeuxPrix,
			      'ListTroisPrix' => $ListTroisPrix,
			      'NbrePremierPrix' => $NbrePremierPrix, 
			      'NbreDeuxPrix' => $NbreDeuxPrix, 
			      'NbreTroisPrix' => $NbreTroisPrix)
			);
		return new Response($content);
	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function RaZ(Request $request)
	{
		$user=$this->getUser();
		$repositoryEquipes = $this->getDoctrine()
		->getManager()
		->getRepository('App:Equipes');

		$repositoryPalmares = $this->getDoctrine()
		->getManager()
		->getRepository('App:Palmares');

		$prix = $repositoryPalmares->findOneByCategorie('prix');
                $em=$this->getDoctrine()->getManager();

		$ListeEquipes = $repositoryEquipes->findAll();
		
		foreach ($ListeEquipes as $equipe)
    		{
            	$equipe->setPrix(null);
				$em->persist($equipe);
				$em->flush();
    		}

		foreach (range('A','Z') as $i)
    		{
    			// On récupère le nom du getter correspondant à l'attribut.
    			$method = 'set'.ucfirst($i);

    			// Si le setter correspondant existe.
    			if (method_exists($prix, $method))
   				{
    				// On appelle le setter.
    				$prix = $prix->$method(null);

					$em->persist($prix);
					$em->flush();
        		} 

        	}
    
      	$content = $this->get('templating')->render('secretariat/RaZ.html.twig');

		return new Response($content);
	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function attrib_prix(Request $request, $niveau)
	{
		//$user=$this->getUser();
		switch ($niveau) 
		{
			case 1:
				$niveau_court = '1er'; 
				$niveau_long = 'premiers';
				break;
			
			case 2:
				$niveau_court = '2ème'; 
				$niveau_long = 'deuxièmes';
				break;

			case 3:
				$niveau_court = '3ème'; 
				$niveau_long = 'troisièmes';
				break;
		}

		$repositoryEquipes = $this->getDoctrine()
		->getManager()
		->getRepository('App:Equipes');

		$repositoryPalmares = $this->getDoctrine()
		->getManager()
		->getRepository('App:Palmares');

		$repositoryClassement = $this->getDoctrine()
		->getManager()
		->getRepository('App:Classement');

		$repositoryPrix = $this->getDoctrine()
		->getManager()
		->getRepository('App:Prix');
		
		$qb = $repositoryPrix->createQueryBuilder('p');
		
		$qb->where('p.classement=:niveau')
		->setParameter('niveau', $niveau_court);
		//$prix_a_attribuer  = $qb->getquery()->getResult();


		//$em=$this->getDoctrine()->getManager();
		
		$ListEquipes = $repositoryEquipes->findByClassement($niveau_court);  

		$NbrePrix=$repositoryClassement
			->findOneByNiveau($niveau_court)
			->getNbreprix(); 

		$prix = $repositoryPalmares->findOneByCategorie('prix');
		$formBuilder=$this->get('form.factory')->createBuilder(FormType::class, $prix);
		
		foreach ($ListEquipes as $equipe) 
		{
			$lettre=strtoupper($equipe->getLettre());
			$titre=$equipe->getTitreProjet();
			$formBuilder
				->add($lettre, EntityType::class, array(
                    'class' => 'App:Prix',
                    'query_builder' => $qb ,
                    'choice_label'=> 'getPrix',
                    'multiple' => false,
                    'label' => $lettre." : ".$titre));
		}
		$formBuilder->add('Enregistrer', SubmitType::class);
		$form=$formBuilder->getForm();

		//Si la requête est en POST 
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
                    {
			// création et gestion du formulaire. 
			$em=$this->getDoctrine()->getManager();
			$em->persist($prix);
			$em->flush();

			foreach (range('A','Z') as $i)
        		{
        		// On récupère le nom du getter correspondant à l'attribut.
        		$method = 'get'.ucfirst($i);

        		// Si le getter correspondant existe.
                        if (method_exists($prix, $method))
                            {
        		// On appelle le setter.
                            $pprix = $prix->$method();
                            if($pprix)
                                {
                                 $equipe = $repositoryEquipes->findOneByLettre($i);
                                 $equipe->setPrix($pprix);
                                 $em->persist($equipe);                          
                                } 
                            }
        		}
                        $em->flush();
	
			$request -> getSession()->getFlashBag()->add('notice', 'Notes bien enregistrées');
			// puis on redirige vers la page de visualisation de cette note dans le tableau de bord
			return $this->redirectToroute('secretariat_attrib_prix', array('niveau'=> $niveau));	
                    }

		// Si on n'est pas en POST, on affiche le formulaire. 
                    $content = $this->get('templating')->render('secretariat/attrib_prix.html.twig',
			array('ListEquipes' => $ListEquipes, 
				'NbrePrix'=>$NbrePrix, 
				'niveau'=>$niveau_long, 
				'form'=>$form->createView(),
                             )
                           );
                    return new Response($content);
	}
	
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function edition_prix(Request $request)
	{
		//$user=$this->getUser();

		$listEquipes = $this->getDoctrine()
			->getManager()
			->getRepository('App:Equipes')
			->getEquipesPrix()
                        ;

		$content = $this->get('templating')->render('secretariat/edition_prix.html.twig', array('listEquipes' => $listEquipes));
		return new Response($content);
	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function edition_visites(Request $request)
	{
		//$user=$this->getUser();
		$listEquipes = $this->getDoctrine()
			->getManager()
			->getRepository('App:Equipes')
			->getEquipesVisites();

		$content = $this->get('templating')->render('secretariat/edition_visites.html.twig', array('listEquipes' => $listEquipes));
		return new Response($content);
	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function attrib_cadeaux(Request $request, $id_equipe)
	{		
		//$user=$this->getUser();
		$repositoryEquipes = $this
			->getDoctrine()
			->getManager()
			->getRepository('App:Equipes')
			;
		$equipe = $repositoryEquipes->find($id_equipe);

		$repositoryCadeaux = $this
			->getDoctrine()
			->getManager()
			->getRepository('App:Cadeaux')
			;
		$cadeau = $equipe->getCadeau();

		$em=$this->getDoctrine()->getManager();

		if(is_null($cadeau))
		{
                    $flag = 0; 
                    $form = $this->createForm(EquipesType::class, $equipe, 
			array(
				'Attrib_Phrases'=> false, 
				'Attrib_Cadeaux'=> true, 
				'Deja_Attrib'=>false,
				));
                    // Si la requête est en post, c'est que le visiteur a soumis le formulaire. 
                    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
			{
			// création et gestion du formulaire. 
			
			$em=$this->getDoctrine()->getManager();
			$em->persist($equipe);
			//$em->flush();
			$cadeau = $equipe->getCadeau();
			$cadeau->setAttribue(1);
			$em->persist($cadeau);
			$em->flush();

			$request -> getSession()->getFlashBag()->add('notice', 'Notes bien enregistrées');
			// puis on redirige vers la page de visualisation de cette note dans le tableau de bord
			return $this->redirectToroute('secretariat_attrib_cadeaux', array('id_equipe'=>$id_equipe));	
			}
                    // Si on n'est pas en POST, alors on affiche le formulaire. 
                    $content = $this->get('templating')->render('secretariat/attrib_cadeaux.html.twig', 
			array(
				'equipe'=>$equipe,
				'form'=>$form->createView(),
				'attribue'=> $flag,
				  ));

                    return new Response($content);
                }

		else
		{
                    $flag = 1; 
                    $em=$this->getDoctrine()->getManager();

                    $form = $this->createForm(EquipesType::class, $equipe, 
			array(
				'Attrib_Phrases'=> false, 
				'Attrib_Cadeaux'=> true, 
				'Deja_Attrib'=>true,
				));
		
                    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
			{
			// création et gestion du formulaire. 

			$em->persist($cadeau);
			$em->flush();	
			if($cadeau->getAttribue())
				{
					$em->persist($equipe);
					$em->flush();					
				}	
			else
				{
					$equipe->setCadeau(NULL);
					$em->persist($equipe);
					$em->flush();					
				}	
			$request -> getSession()->getFlashBag()->add('notice', 'Notes bien enregistrées');
			// puis on redirige vers la page de visualisation de cette note dans le tableau de bord
			return $this->redirectToroute('secretariat_attrib_cadeaux', array('id_equipe'=>$id_equipe));	
			}
		// Si on n'est pas en POST, alors on affiche le formulaire. 
                    $content = $this->get('templating')->render('secretariat/attrib_cadeaux.html.twig', 
			array(
				'equipe'=>$equipe,
				'form'=>$form->createView(),
				'attribue'=> $flag,
				  ));

                    return new Response($content);
		}

	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/	
	public function lescadeaux(Request $request, $compteur)
	{
		$user=$this->getUser();
		$repositoryCadeaux = $this
			->getDoctrine()
			->getManager()
			->getRepository('App:Cadeaux')
			;

		$repositoryEquipes = $this
			->getDoctrine()
			->getManager()
			->getRepository('App:Equipes')
			;

		$repositoryPrix = $this
			->getDoctrine()
			->getManager()
			->getRepository('App:Prix')
			;

		$nbreEquipes = $repositoryEquipes
			->createQueryBuilder('e')
                        ->select('COUNT(e)') 
		 	->getQuery()
		 	->getSingleScalarResult(); 

		$listEquipesCadeaux = $repositoryEquipes->getEquipesCadeaux();

		$listEquipesPrix = $repositoryEquipes->getEquipesPrix();
	
		$equipe = $repositoryEquipes->findOneByRang($compteur);
		if (is_null($equipe)) 
		{
			$content = $this->get('templating')->render('secretariat/edition_cadeaux.html.twig', 
			array(
			'listEquipesCadeaux' => $listEquipesCadeaux,
			'listEquipesPrix' => $listEquipesPrix,
			'nbreEquipes'=>$nbreEquipes,
			'compteur'=>$compteur,));
			return new Response($content);

		}
		$id_equipe = $equipe->getId();

		$cadeau = $equipe->getCadeau();

		$em=$this->getDoctrine()->getManager();

		if(is_null($cadeau))
		{
		$flag = 0; 
		$form = $this->createForm(EquipesType::class, $equipe, 
			array(
				'Attrib_Phrases'=> false, 
				'Attrib_Cadeaux'=> true, 
				'Deja_Attrib'=>false,
				));
		// Si la requête est en post, c'est que le visiteur a soumis le formulaire. 
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
			{
			// création et gestion du formulaire. 
			
			$em=$this->getDoctrine()->getManager();
			$em->persist($equipe);
			//$em->flush();
			$cadeau = $equipe->getCadeau();
			$cadeau->setAttribue(1);
			$em->persist($cadeau);
			$em->flush();

			$request -> getSession()->getFlashBag()->add('notice', 'Cadeaux bien enregistrés');
			// puis on redirige vers la page de visualisation de cette note dans le tableau de bord
			if($compteur<=$nbreEquipes)
				{
					return $this->redirectToroute('secretariat_lescadeaux',array('compteur'=>$compteur+1));	
				}
			else 
				{
					$content = $this->get('templating')->render('secretariat/edition_cadeaux.html.twig', 
					array('equipe'=>$equipe,
					'form'=>$form->createView(),
					'attribue'=> $flag,
					'listEquipesCadeaux' => $listEquipesCadeaux,
					'listEquipesPrix' => $listEquipesPrix,
					'nbreEquipes'=>$nbreEquipes,
					'compteur'=>$compteur,));
					return new Response($content);
				}
			}
		// Si on n'est pas en POST, alors on affiche le formulaire. 
		$content = $this->get('templating')->render('secretariat/edition_cadeaux.html.twig', 
			array('equipe'=>$equipe,
				'form'=>$form->createView(),
				'attribue'=> $flag,
				'listEquipesCadeaux' => $listEquipesCadeaux,
				'listEquipesPrix' => $listEquipesPrix,
				'nbreEquipes'=>$nbreEquipes,
				'compteur'=>$compteur,));

		return new Response($content);
		}

		else
		{
		$flag = 1; 
		$em=$this->getDoctrine()->getManager();

		$form = $this->createForm(EquipesType::class, $equipe, 
			array(
				'Attrib_Phrases'=> false, 
				'Attrib_Cadeaux'=> true, 
				'Deja_Attrib'=>true,
				));
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
			{
			// création et gestion du formulaire. 

			$em->persist($cadeau);
			//$em->flush();	
			if($cadeau->getAttribue())
				{
					$em->persist($equipe);
					$em->flush();					
				}	
			else
				{
					$equipe->setCadeau(NULL);
					$em->persist($equipe);
					$em->flush();					
				}	
			$request -> getSession()->getFlashBag()->add('notice', 'Notes bien enregistrées');
			// puis on redirige vers la page de visualisation de cette note dans le tableau de bord
			
			if($compteur<$nbreEquipes)
				{
					return $this->redirectToroute('secretariat_lescadeaux',array('compteur'=>$compteur+1));	
				}
			else
				{
					$content = $this->get('templating')->render('secretariat/edition_cadeaux.html.twig', 
					array('equipe'=>$equipe,
					'form'=>$form->createView(),
					'attribue'=> $flag,
					'listEquipesCadeaux' => $listEquipesCadeaux,
					'listEquipesPrix' => $listEquipesPrix,
					'nbreEquipes'=>$nbreEquipes,
					'compteur'=>$compteur,));
					return new Response($content);

				}
				}

		// Si on n'est pas en POST, alors on affiche le formulaire. 
		$content = $this->get('templating')->render('secretariat/edition_cadeaux.html.twig', 
			array('equipe'=>$equipe,
				'form'=>$form->createView(),
				'attribue'=> $flag,
				'listEquipesCadeaux' => $listEquipesCadeaux,
				'listEquipesPrix' => $listEquipesPrix,
				'nbreEquipes'=>$nbreEquipes,
				'compteur'=>$compteur,));
		return new Response($content);
	}
	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function edition_cadeaux(Request $request)
	{
		$user=$this->getUser();
		$listEquipes = $this->getDoctrine()
			->getManager()
			->getRepository('App:Equipes')
			->getEquipesCadeaux();

		$content = $this->get('templating')->render('secretariat/edition_cadeaux2.html.twig', array('listEquipes' => $listEquipes));
		return new Response($content);
	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function edition_phrases(Request $request)
	{
		$user=$this->getUser();
		$listEquipes = $this->getDoctrine()
			->getManager()
			->getRepository('App:Equipes')
			->getEquipesPhrases();

		$content = $this->get('templating')->render('secretariat/edition_phrases.html.twig', array('listEquipes' => $listEquipes));
		return new Response($content);
	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function tableau_palmares_complet(Request $request)
	{
		$user=$this->getUser();
		$listEquipes = $this->getDoctrine()
			->getManager()
			->getRepository('App:Equipes')
			->getEquipesPalmares();

		$repositoryEleves = $this
			->getDoctrine()
			->getManager()
			->getRepository('App:Eleves')
			;

		foreach ($listEquipes as $equipe) 
		{
			$lettre=$equipe->getLettre();
			$lesEleves[$lettre] = $repositoryEleves->findByLettreEquipe($lettre);
		}

		$content = $this->get('templating')->render('secretariat/edition_palmares_complet.html.twig', 
			array('listEquipes' => $listEquipes,
				  'lesEleves'=>$lesEleves));
		return new Response($content);

	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function tableau_excel_palmares_site(Request $request)
	{
		$user=$this->getUser();
		$listEquipes = $this->getDoctrine()
			->getManager()
			->getRepository('App:Equipes')
			->getEquipesPalmares();

		$repositoryEleves = $this
			->getDoctrine()
			->getManager()
			->getRepository('App:Eleves');

		foreach ($listEquipes as $equipe) 
		{
			$lettre=$equipe->getLettre();
			$lesEleves[$lettre] = $repositoryEleves->findByLettreEquipe($lettre);
		}

		# create an empty object 
		$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

		$phpExcelObject->getProperties()->setCreator("OdpF")
           ->setLastModifiedBy("OdpF")
           ->setTitle("Palmarès de la 26ème édition - Février 2019")
           ->setSubject("Palmarès")
           ->setDescription("Palmarès avec Office 2005 XLSX, generated using PHP classes.")
           ->setKeywords("office 2005 openxml php")
           ->setCategory("Test result file");

        $phpExcelObject->getDefaultStyle()->getFont()->setName('Calibri');
       	$phpExcelObject->getDefaultStyle()->getFont()->setSize(7);
       	$phpExcelObject->getDefaultStyle()->getAlignment()->setWrapText(true);

       	$ligne=3;
       	$phpExcelObject->setActiveSheetIndex(0)
           ->setCellValue('B'.$ligne, 'Académie')
           ->setCellValue('C'.$ligne, 'Lycée, sujet, élèves')
           ->setCellValue('D'.$ligne, 'Professeurs')
           ->setCellValue('F'.$ligne, 'Prix spécial - Visite de laboratoire - Prix en matériel scientifique');

        $ligne = $ligne+1; 

       	foreach ($listEquipes as $equipe) 
       	{
       	$lettre = $equipe->getLettre();

       	$ligne4 = $ligne + 3;
		$phpExcelObject->getActiveSheet()->mergeCells('B'.$ligne.':B'.$ligne4);
       	$phpExcelObject->setActiveSheetIndex(0)
           ->setCellValue('B'.$ligne, strtoupper($equipe->getInfoequipe()->getLyceeAcademie()))
		   ->setCellValue('C'.$ligne, $equipe->getInfoequipe()->getDenominationLycee().' '.$equipe->getInfoequipe()->getnomLycee()." - ".$equipe->getInfoequipe()->getLyceeLocalite() )
           ->setCellValue('D'.$ligne, $equipe->getInfoequipe()->getPrenomProf1().' '.strtoupper($equipe->getInfoequipe()->getnomProf1() ))
           ->setCellValue('E'.$ligne, $equipe->getClassement().' '.'prix')
           ->setCellValue('F'.$ligne, $equipe->getPhrases()->getPhrase().' '.$equipe->getLiaison()->getLiaison().' '.$equipe->getPhrases()->getPrix());

       	$ligne = $ligne+1; 
       	$ligne3 = $ligne + 1; 
       	$phpExcelObject->getActiveSheet()->mergeCells('C'.$ligne.':C'.$ligne3);
       	$phpExcelObject->setActiveSheetIndex(0)
           ->setCellValue('C'.$ligne, $equipe->getTitreProjet())
           ->setCellValue('D'.$ligne, $equipe->getInfoequipe()->getPrenomProf2().' '.strtoupper($equipe->getInfoequipe()->getnomProf2() ))
           ->setCellValue('F'.$ligne, $equipe->getPrix()->getPrix() );

           if ($equipe->getClassement()=='1er') 
           {
           	$phpExcelObject->setActiveSheetIndex(0)->setCellValue('E'.$ligne, PRIX::PREMIER.'€');
           }
           elseif ($equipe->getClassement()=='2ème') 
           {
           	$phpExcelObject->setActiveSheetIndex(0)->setCellValue('E'.$ligne, PRIX::DEUXIEME.'€' );
           }
           else
           {
           	$phpExcelObject->setActiveSheetIndex(0)->setCellValue('E'.$ligne, PRIX::TROISIEME.'€');
           }
           
		
       	$ligne = $ligne+1; 
       	$phpExcelObject->setActiveSheetIndex(0)
           ->setCellValue('E'.$ligne, 'Visite :')
           ->setCellValue('F'.$ligne, $equipe->getVisite()->getIntitule());

       	$ligne = $ligne+1; 
       	$phpExcelObject->getActiveSheet()->mergeCells('E'.$ligne.':F'.$ligne);
       	$phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('E'.$ligne, $equipe->getCadeau()->getContenu().' offert par '.$equipe->getCadeau()->getFournisseur().' d\'une valeur de '.$equipe->getCadeau()->getMontant().' euros.' );

        $listeleves='';
        $nbre = count($lesEleves[$lettre]);
		$eleves = $lesEleves[$lettre];

		for ($i=1; $i < $nbre-1 ; $i++) 
		{ 
			$eleve = $eleves[$i];
			$prenom=$eleve->getPrenom();
			$nom=strtoupper($eleve->getNom());
			$listeleves.=$prenom.' '.$nom.', ';
			
		}
			$eleve = $eleves[$i];
			$prenom=$eleve->getPrenom();
			$nom=strtoupper($eleve->getNom());
			$listeleves.=$prenom.' '.$nom;

/*		foreach ($lesEleves[$lettre] as $eleves) 
		{
			$prenom=$eleves->getPrenom();
			$nom=$eleves->getNom();
			$listeleves.=$prenom.' '.$nom.', ';
		}
*/
		$phpExcelObject->setActiveSheetIndex(0)->setCellValue('C'.$ligne, $listeleves );

        $ligne = $ligne+1; 
       	}

		$phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(8.17);
		$phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth(32.17);
		$phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth(11.17);
		$phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth(5.17);
		$phpExcelObject->getActiveSheet()->getColumnDimension('F')->setWidth(39.17);
		

       	$phpExcelObject->getActiveSheet()->setTitle('Palmarès JURY ');
       	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
       	$phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'palmares.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;        

	}
	
	/**
	* @Security("has_role('ROLE_SUPER_ADMIN')")
	*/
	public function tableau_excel_palmares_jury(Request $request)
	{
		$user=$this->getUser();
		$listEquipes = $this->getDoctrine()
			->getManager()
			->getRepository('App:Equipes')
			->getEquipesPalmaresJury();

		$repositoryEleves = $this
			->getDoctrine()
			->getManager()
			->getRepository('App:Eleves');

		foreach ($listEquipes as $equipe) 
		{
			$lettre=$equipe->getLettre();
			$lesEleves[$lettre] = $repositoryEleves->findByLettreEquipe($lettre);
		}

		# create an empty object 
		$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

		$phpExcelObject->getProperties()->setCreator("OdpF")
           ->setLastModifiedBy("OdpF")
           ->setTitle("Deliberation du Jury")
           ->setSubject("Deliberation du Jury")
           ->setDescription("Deliberation du Jury avec Office 2005 XLSX, generated using PHP classes.")
           ->setKeywords("office 2005 openxml php")
           ->setCategory("Test result file");

        $phpExcelObject->getDefaultStyle()->getFont()->setName('Calibri');
       	$phpExcelObject->getDefaultStyle()->getFont()->setSize(14);
       	$phpExcelObject->getDefaultStyle()->getAlignment()->setWrapText(true);
		
		$styleTitre=array('font'=>array(
                                'bold'=>true,
                                'size'=>16,
                                'name'=>'Calibri',
                                ),                  			
                  		);

       	$ligne=1;
       	foreach ($listEquipes as $equipe) 
       	{
       	$lettre = $equipe->getLettre();

       	$phpExcelObject->setActiveSheetIndex(0)
           ->setCellValue('A'.$ligne, 'Prix spécial')
           ->setCellValue('B'.$ligne, $equipe->getClassement())
           ->setCellValue('C'.$ligne, $equipe->getPrix()->getPrix());
        
        $phpExcelObject->getActiveSheet()
        	->getStyle('A'.$ligne.':C'.$ligne)
        	->applyFromArray($styleTitre) ; 
       	
       	$ligne = $ligne+1; 
       	$phpExcelObject->getActiveSheet()->mergeCells('A'.$ligne.':C'.$ligne);
       	$phpExcelObject->setActiveSheetIndex(0)
           ->setCellValue('A'.$ligne, $equipe->getPhrases()->getPhrase().' '.$equipe->getLiaison()->getLiaison().' '.$equipe->getPhrases()->getPrix());
		$phpExcelObject->getActiveSheet()->getStyle('A'.$ligne)->getAlignment()->setWrapText(true);


		
		$phpExcelObject->getActiveSheet()
      	->getStyle('A1')->applyFromArray($styleTitre);

       	$ligne = $ligne+1; 
       	$lignep = $ligne + 1; 
       	$phpExcelObject->getActiveSheet()->mergeCells('A'.$ligne.':A'.$lignep);
		$phpExcelObject->setActiveSheetIndex(0)
           ->setCellValue('A'.$ligne, 'Vous êtes l\'équipe')
           ->setCellValue('B'.$ligne, $equipe->getLettre())
           ->setCellValue('C'.$ligne, $equipe->getTitreProjet());
        $phpExcelObject->getActiveSheet()->getStyle('C'.$ligne)->getAlignment()->setWrapText(true);

       	$ligne = $ligne+1; 
       	$phpExcelObject->setActiveSheetIndex(0)
           ->setCellValue('B'.$ligne, 'AC. '.$equipe->getInfoequipe()->getLyceeAcademie())
           ->setCellValue('C'.$ligne, $equipe->getInfoequipe()->getDenominationLycee().' '.$equipe->getInfoequipe()->getnomLycee()."\n".$equipe->getInfoequipe()->getLyceeLocalite() );
 

        $phpExcelObject->getActiveSheet()->getStyle('B'.$ligne)->getAlignment()->setWrapText(true);
        $phpExcelObject->getActiveSheet()->getStyle('C'.$ligne)->getAlignment()->setWrapText(true);


       	$ligne = $ligne+1; 
       	$lignep = $ligne + 1; 
       	$phpExcelObject->getActiveSheet()->mergeCells('A'.$ligne.':A'.$lignep);
       	$phpExcelObject->setActiveSheetIndex(0)
           ->setCellValue('A'.$ligne, 'Nos partenaires vous offrent')
           ->setCellValue('B'.$ligne, 'une visite de laboratoire : ')
           ->setCellValue('C'.$ligne, $equipe->getVisite()->getIntitule());
		$phpExcelObject->getActiveSheet()->getStyle('A'.$ligne)->getAlignment()->setWrapText(true);
		$phpExcelObject->getActiveSheet()->getStyle('B'.$ligne)->getAlignment()->setWrapText(true);
		$phpExcelObject->getActiveSheet()->getStyle('C'.$ligne)->getAlignment()->setWrapText(true);

       	$ligne = $ligne+1; 
       	$phpExcelObject->setActiveSheetIndex(0)
           ->setCellValue('B'.$ligne, 'du matériel scientifique : ')
           ->setCellValue('C'.$ligne, $equipe->getCadeau()->getContenu().' offert par '.$equipe->getCadeau()->getFournisseur().' d\'une valeur de '.$equipe->getCadeau()->getMontant().' euros.' );
		$phpExcelObject->getActiveSheet()->getStyle('B'.$ligne)->getAlignment()->setWrapText(true);
		$phpExcelObject->getActiveSheet()->getStyle('C'.$ligne)->getAlignment()->setWrapText(true);

       	$ligne = $ligne+2; 

       	}
		$phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth(27);
		$phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth(68);

		$phpExcelObject->getActiveSheet()->getPageSetup()->setFitToWidth(1);
       	$phpExcelObject->getActiveSheet()->getPageSetup()->setFitToHeight(0);

		$phpExcelObject->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
		$phpExcelObject->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

		$phpExcelObject->getActiveSheet()->getHeaderFooter()->setOddFooter('RPage &P sur &N');


       	$phpExcelObject->getActiveSheet()->setTitle('Proclamation du palmarès ');
       	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
       	$phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'proclamation.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;        
	}

}

