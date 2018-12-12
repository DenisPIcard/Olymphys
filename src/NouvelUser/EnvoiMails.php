<?php
//App/NouvelUser/EnvoiMails.php

namespace App\NouvelUser;

use Symfony\Component\HttpFoundation\Response;
use App\Application\Sonata\UserBundle\Entity\User;

class EnvoiMails
{
  protected $mailer;

  public function __construct(\Swift_Mailer $mailer)
  {
    $this->mailer = $mailer;
  }

  public function notifyByEmail(User $user)
  {
    $message = (new \Swift_Message("Connexion d'un nouvel utilisateur "))
      ->setFrom('webmestre2@olymphys.fr')
      ->setTo('info@olymphys.fr')
      ->setBody("L'utilisateur '".$user->getUsername()."' a terminé son inscription")
    ;

    $this->mailer->send($message);
  }
}