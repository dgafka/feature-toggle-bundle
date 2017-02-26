<?php

namespace CleanCode\Bundle\FeatureToggleBundle\DependencyInjection\ContainerBuilder;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class InjectableCompilerPass
 * @package CleanCode\Bundle\InjectableBundle\DependencyInjection
 * @author Dariusz Gafka <dgafka.mail@gmail.com>
 */
class FeatureToggleCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds(
            'toggle'
        );
        $setUp = [];
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $for = $attributes['for'];
                $when = $attributes['when'];
                $on = $attributes['on'];
                $parameter = $container->getParameter($on);
                if ($parameter == $when) {
                    if (array_key_exists($for, $setUp)) {
                        throw new \RuntimeException("{$for} has two toggles at the same time {$setUp[$for]} and {$id}");
                    }
                    $container->setAlias($for, $id);
                    $setUp[$for] = $id;
                }
            }
        }
    }
}