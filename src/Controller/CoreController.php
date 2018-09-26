<?php
// src/Controller/CoreController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller
{

  public function index()
  {
    return $this->render('core/index.html.twig');

    
  }

  // La page de contact
  public function contact(Request $request)
  {
    // On récupère la session depuis la requête, en argument du contrôleur
    $session = $request->getSession();
    // Et on définit notre message
    $session->getFlashBag()->add('info', 'La page de contact n’est pas encore disponible, merci de revenir plus tard.');

    // Enfin, on redirige simplement vers la page d'accueil
    return $this->redirectToRoute('odpf_core_home');

    
  }
}
