<?php
// App/NouvelUser/InscritListener.php

namespace App\NouvelUser;


use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use App\Utils\EnvoiMails;

class InscritListener implements EventSubscriberInterface
{
  protected $notificator;

  public function __construct(EnvoiMails $notificator)
  {
    $this->notificator = $notificator;
  }
    /**
     * {@inheritdoc}
     */
   public static function getSubscribedEvents()
   {
        return array(
            FOSUserEvents::REGISTRATION_CONFIRMED => 'RegistrationCompleted',
        );
   }
    
   public function RegistrationCompleted(FilterUserResponseEvent $event)
   {
      $from = 'webmestre2@olymphys.fr';
      $to = 'info@olymphys.fr';
      $sujet="Connexion d'un nouvel utilisateur";
      $user=$event->getUser;
      $body = "L'utilisateur ".$user->getUsername()." a terminé son inscription";
      $this->notificator->send($from, $to, $sujet, $body);
   }

}
