<?php

namespace Prodigious\Sonata\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Prodigious\Sonata\MenuBundle\Model\MenuItem as BaseMenuItem;

#[ORM\Entity(repositoryClass: \Prodigious\Sonata\MenuBundle\Repository\MenuItemRepository::class)]
#[ORM\Table(name: 'sonata_menu_item')]
class MenuItem extends BaseMenuItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
