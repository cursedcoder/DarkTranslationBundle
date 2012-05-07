<?php

namespace Dark\TranslationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class DarkTranslationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $config['build']['path'] = $container->getParameterBag()->resolveValue($config['build']['path']);
        $config['source']['base_dir'] = $container->getParameterBag()->resolveValue($config['source']['base_dir']);
        $config['source']['from'] = $container->getParameterBag()->resolveValue($config['source']['from']);
        $config['source']['to'] = $container->getParameterBag()->resolveValue($config['source']['to']);

        $container->setParameter('dark_translation.build.path', $config['build']['path']);
        $container->setParameter('dark_translation.source.base_dir', $config['source']['base_dir']);
        $container->setParameter('dark_translation.source.from', $config['source']['from']);
        $container->setParameter('dark_translation.source.to', $config['source']['to']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}