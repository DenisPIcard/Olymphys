<?php
// src/Controller/CoreController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CoreController extends AbstractController
{
    /**
     * @Route("/", name="core_home")
     */
  public function index(SessionInterface $session)
  {
    
    $user=$this->getUser();
    if (null != $user)
    {
 
     $session->set('user', $user);
    }
    
    return $this->render('core/index.html.twig');
  }
}
