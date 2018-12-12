<?php
// App/NouvelUser/InscritListener.php

namespace App\NouvelUser;


use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\NouvelUser\EnvoiMails;

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
      $this->notificator->notifyByEmail($event->getUser());
   }

}
