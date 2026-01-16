<?php

namespace Prodigious\Sonata\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Prodigious\Sonata\MenuBundle\Model\Menu as BaseMenu;

#[ORM\Entity(repositoryClass: \Prodigious\Sonata\MenuBundle\Repository\MenuRepository::class)]
#[ORM\Table(name: 'sonata_menu')]
class Menu extends BaseMenu
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
