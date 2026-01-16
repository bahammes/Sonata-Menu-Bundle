<?php

namespace Prodigious\Sonata\MenuBundle\DependencyInjection\Compiler;

use Prodigious\Sonata\MenuBundle\Entity\Menu;
use Prodigious\Sonata\MenuBundle\Entity\MenuItem;
use Prodigious\Sonata\MenuBundle\Model\MenuInterface;
use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineResolveTargetEntityPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');

        $menuTarget = $container->getParameter('sonata_menu.entity.menu'); 
        $menuItemTarget = $container->getParameter('sonata_menu.entity.menu_item'); 

        $definition
            ->addMethodCall('addResolveTargetEntity',[
                    MenuInterface::class,
                    $menuTarget,
                    [],
                ]
            )
            ->addMethodCall('addResolveTargetEntity',[
                    MenuItemInterface::class,
                    $menuItemTarget,
                    [],
                ]
            );

        if ($menuTarget !== Menu::class) {
            $this->removeEntityMappingV4($definition, Menu::class, $menuTarget);
            
        }

        if ($menuItemTarget !== MenuItem::class) {
            $this->removeEntityMappingV4($definition, MenuItem::class, $menuItemTarget);
        }

        $definition->addTag('doctrine.event_subscriber', ['connection' => 'default']);
    }

    // Ignore orm objects in Entity folder
    protected function removeEntityMappingV4($definition, $origin, $target)
    {
        $definition->addMethodCall('addResolveTargetEntity',[
                $origin,
                $target,
                [],
            ]
        );
    }
}
