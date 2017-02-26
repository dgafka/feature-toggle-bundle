<?php
/**
 * Created by PhpStorm.
 * User: dgafka
 * Date: 26.02.17
 * Time: 17:04
 */

namespace CleanCode\Bundle\FeatureToggleBundle;

use CleanCode\Bundle\FeatureToggleBundle\DependencyInjection\ContainerBuilder\FeatureToggleCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class InjectableBundle
 * @package CleanCode\Bundle\InjectableBundle
 * @author Dariusz Gafka <dgafka.mail@gmail.com>
 */
class FeatureToggleBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FeatureToggleCompilerPass());
    }
}