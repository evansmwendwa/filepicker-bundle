<?php

namespace Evans\FilepickerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('evans_filepicker');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $this->registerOptions($rootNode);

        return $treeBuilder;
    }

    /**
    * Add configuration Picker
    *
    * @param ArrayNodeDefinition $rootNode
    */
   private function registerOptions(ArrayNodeDefinition $rootNode)
   {
       $rootNode
           ->children()
               ->scalarNode('uploads_destination')
                    ->info('Provide uploads destination path here. Relative to web directory')
                    ->defaultValue('/uploads/chooser')
               ->end()

               ->booleanNode('allow_uploads')
                    ->info('Do you want users to be able to use the uploads feature')
                    ->defaultValue(true)
               ->end()

               ->booleanNode('group_files')
                    ->info('Do you want files grouped by months')
                    ->defaultValue(false)
               ->end()

               ->arrayNode('allowed_mimes')
                    ->prototype('scalar')
                    ->end()
                    ->defaultValue(['image/jpeg','image/png','image/gif'])
               ->end()

           ->end()
       ;
   }
}
