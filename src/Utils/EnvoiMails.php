<?php
//App/Utils/EnvoiMails.php

namespace App\Utils;

class EnvoiMails
{
  protected $mailer;

  public function __construct(\Swift_Mailer $mailer)
    {
    $this->mailer = $mailer;
    }


  public function send($from, $to, $cc='', $sujet, $body, $attachments = [])
      {
       $message = new \Swift_Message($sujet, $body);
       $message->setFrom($from);
       $message->setTo($to);
       $message->setCc($cc);
       if (count($attachments) > 0) {
            foreach ($attachments as $file) {
                switch ($file) {
                    case $file instanceof \SplFileInfo:
                        $path = $file->getPathName();
                        $nomfic = $file->getFileName();
                        $message->attach(\Swift_Attachment::fromPath($path),$nomfic);
                        break;
                    case $file instanceof UploadedFile:
                        //$path= $file->getRealPath();
                        //$nomfic=$file->getClientOriginalName();
                        $message->attach(\Swift_Attachment::fromPath($file->getRealPath(), $file->getMimeType()),$file->setFilename($file->getClientOriginalName()));             
                        break;
                }              
            }
            dump($message);
        }
        
/*
        if($cde) {
            
        }
*/        return $this->mailer->send($message);
    }
        
    public function generateUniqueFileName() {
        return md5(uniqid());
    }
}