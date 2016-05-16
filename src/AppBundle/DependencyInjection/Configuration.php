<?php
namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app');

        $rootNode
            ->children()
                ->arrayNode('social_login')
					->children()
						->arrayNode('facebook')
							->children()
								->booleanNode('enabled')->defaultTrue()->end()
								->scalarNode('base_url')->end()
								->scalarNode('name')->end()
								->scalarNode('app_id')->end()
								->scalarNode('app_secret')->end()
								->scalarNode('scope')->end()
							->end() // end facebook children
						->end() // end facebook array
					->end() // end social_login children
				->end()// end social_login array
            ->end(); // end root


        return $treeBuilder;
    }
}
