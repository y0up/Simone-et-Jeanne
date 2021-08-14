<?php

namespace App\EventSubscriber;

use App\Entity\Caracteristic;
use App\Entity\CaracteristicDetail;
use App\Entity\Category;
use App\Entity\OrderDetail;
use App\Entity\Product;
use DateTime;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setProductSlugAndDate'],
        ];
    }

    public function setProductSlugAndDate( BeforeEntityPersistedEvent $event )
    {
        $entity = $event->getEntityInstance();
        
        if (!($entity instanceof Product || $entity instanceof Category || $entity instanceof CaracteristicDetail || $entity instanceof Caracteristic || $entity instanceof OrderDetail)) {
            return;
        }
        
        if ($entity instanceof Product || $entity instanceof Category) {
            $slug = $this->slugger->slug($entity->getName());
            $entity->setSlug($slug);
        }

        $now = new DateTime('now');
        $entity->setCreatedAt($now);
    }
}
