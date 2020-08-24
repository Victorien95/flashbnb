<?php


namespace App\Service;

use App\Entity\Ad;
use App\Entity\Image;
use App\Repository\AdRepository;
use Vich\UploaderBundle\Event\Event;


class VichUploaderEventListener
{
    public function onVichUploaderPreUpload(Event $event)
    {
        $object = $event->getObject();
        $mapping = $event->getMapping();

        // do your stuff with $object and/or $mapping...
    }

    public function onVichUploaderPostRemove(Event $event)
    {

        $object = $event->getObject();
        $mapping = $event->getMapping();

    }

}