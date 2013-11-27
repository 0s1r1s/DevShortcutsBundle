<?php

/*
 * This file is part of the DevShortcutsBundle.
 *
 * (c) Laurin Wandzioch <laurin@wandzioch.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Osiris\Bundle\DevShortcutsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class OsirisDevShortcutsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

//        if (!isset($config['path_to_fixtures'])) {
//            throw new \InvalidArgumentException(
//                'The "path_to_fixtures" option must be set'
//            );
//        }

        $container->setParameter(
            'osiris_dev_shortcuts.path_to_fixtures',
            $config['path_to_fixtures']
        );
    }
}
