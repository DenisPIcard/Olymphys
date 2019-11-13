<?php
// src/Controller/ComiteController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Finder\Finder;


use App\Utils\ExcelCreate;
use App\Utils\EnvoiMails;


class ComiteController extends AbstractController
    {
     /**
     * @Security("is_granted('ROLE_COMITE')")
     * @Route("comite/accueil", name="comite_accueil")
     */
	public function accueil()
        {
        $content = $this->get('templating')->render('comite/accueil.html.twig');	
        return new Response($content);
        }
         
     /**
     * @Security("is_granted('ROLE_COMITE')")
     * @Route("comite/frais_lignes", name="comite_frais_lignes")
     */
	public function frais_lignes(Request $request)
        {
        $user=$this->getUser();
        
        $repositoryEdition=$this
			->getDoctrine()
			->getManager()
			->getRepository('App:Edition');
                
        $ed=$repositoryEdition->findOneByEd('ed');
        $edition=$ed->getEdition();
         
        $task= ['message' => '1'];
        $form = $this->createFormBuilder($task)
            ->add('nblignes', IntegerType::class, ['label' => 'De combien de lignes avez vous besoin'])
            ->add('Entrée', SubmitType::class)
            ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data=$form->getData();
            $nblig=$data['nblignes'];
            
            return $this->redirectToroute('comite_frais', ['nblig' => $nblig] );
            }
        $content = $this->get('templating')->render('comite/frais_lignes.html.twig', ['edition' =>$edition, 'form'=>$form->createView()]);		
        return new Response($content);
        }
        
     /**
     * @Security("is_granted('ROLE_COMITE')")
     * @Route("comite/frais/{nblig}", name="comite_frais", requirements={"nblig"="\d{1}|\d{2}"})
     */
     public function frais(Request $request, ExcelCreate $create, $nblig, $data=[])
        {
            $user=$this->getUser();
            $repositoryEdition=$this
			->getDoctrine()
			->getManager()
			->getRepository('App:Edition');
                
            $ed=$repositoryEdition->findOneByEd('ed');
            $edition=$ed->getEdition();
            
            $task=['nblig' => $nblig];
            
            $formBuilder = $this->createFormBuilder($task);
            
            for ($i=1 ; $i<=$nblig ; $i++) {
                if ([] !== $data) {
                    $date=$data['date'.$i];
                    $depl=$data['deplacement'.$i];
                }
                else {
                    $date='now';
                    $depl='0';
                }
                
                $formBuilder  ->add('date'.$i, DateType::class, ['widget' => 'single_text'])
                        ->add('designation'.$i, TextType::class)
                        ->add('deplacement'.$i, MoneyType::class, ['data'=>$depl, 'required'=>false])
                        ->add('repas'.$i, MoneyType::class, ['required'=>false])
                        ->add('fournitures'.$i, MoneyType::class, ['required'=>false])
                        ->add('poste'.$i, MoneyType::class, ['required'=>false])
                        ->add('impressions'.$i, MoneyType::class, ['required'=>false])
                        ->add('autres'.$i, MoneyType::class, ['required'=>false]);
              }
            $formBuilder->add('iban1', TextType::class, ['required'=>false]);
            for ($j=2; $j<8; $j++) {
                $formBuilder->add('iban'.$j, NumberType::class, ['required'=>false]);
                }
            $formBuilder->add('Entrée', SubmitType::class);
            $form=$formBuilder->getForm();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                    $data=$form->getData();
                $nblig=$data['nblig'];
                
                $fichier = $create->excelfrais($user, $edition, $data, $nblig);

                 return $this->redirectToroute('comite_frais', ['nblig' => $nblig, 'data'=>$data] );
                }
            $content = $this->get('templating')->render('comite/frais.html.twig', [ 'edition' => $edition, 'nblig'=>$nblig,'form'=>$form->createView()]);		
            return new Response($content);
        
        }

     /**
     * @Security("is_granted('ROLE_COMITE')")
     * @Route("comite/envoi_frais", name="comite_envoi_frais")
     */
     public function envoi_frais(Request $request, EnvoiMails $mail )
        {
        $user=$this->getUser();

        $task=['nblig' => 2 ];   
        $formBuilder = $this->createFormBuilder($task);
        for ($i=1;$i<4;$i++) {
         $formBuilder->add('Fichier'.$i, FileType::class, ['required'=>false]);   
        }
        $formBuilder->add('Entrée', SubmitType::class);
        $form=$formBuilder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom=$user->getLastname();
            $auj= \strftime('%d-%b-%g');
            for ($i=1;$i<4;$i++) {
               $fichnouv=$form['Fichier'.$i]->getData(); 
               if (null !== $fichnouv) {
                    $fileName = $nom.'_'.'Piece'.$i.'_'.$auj.'.'.$fichnouv->guessExtension();
                    try {
                        $fichnouv->move(
                        $this->getParameter('frais_directory'),
                            $fileName
                        );
                    } catch (FileException $e) {
                // ... handle exception if something happens during file upload
                    }
                 }
            }
            $mailuser=$user->getEmail();
            $chemin=$this->getParameter('frais_directory');
            $reg = '*'.$nom.'*.*';
            $finder = new Finder();          
            $finder->files()->in([__DIR__,$chemin])->name($reg)->date('> now - 5 hours');
            $attachments=iterator_to_array($finder);
            $sujet='Envoi de frais OdPF';
            $body = $this->renderView('emails/frais.html.twig',['nom' => $nom]);
            $from=$mailuser;
            $to='denis.picard48@orange.fr';
            $cc='info@olymphys.fr';
            $mail->send($from, $to, $cc, $sujet, $body, $attachments);

             return $this->redirectToroute('comite_accueil' );
        }
        $content = $this->get('templating')->render('comite/envoi_frais.html.twig', ['user'=>$user, 'form'=>$form->createView()]);	
        return new Response($content);
        }
}