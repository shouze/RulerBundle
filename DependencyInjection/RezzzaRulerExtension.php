<?php

namespace Rezzza\RulerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * RezzzaRulerExtension
 *
 * @uses Extension
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class RezzzaRulerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/services'));
        $loader->load('context_builder.xml');
        $loader->load('event.xml');
        $loader->load('inference.xml');
        $loader->load('ruler.xml');

        $processor = new Processor();
        $config    = $processor->processConfiguration(new Configuration(), $configs);

        $cbContainer = $container->getDefinition('rezzza.ruler.context_builder.container');
        foreach ($config['context_builder'] as $name => $service) {
            $cbContainer->addMethodCall('add', array($name, new Reference($service)));
        }

        $container->setParameter('rezzza.ruler.property_access', $config['property_access']);
        $container->setParameter('rezzza.ruler.inferences', $config['inferences']);
        $container->setParameter('rezzza.ruler.events', $config['events']);
    }
}
