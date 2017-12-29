<?php

namespace Application;

use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Mvc\Router\Http\Segment;

return [
	'controllers' => [
		'abstract_factories' => [
			Controller\AbstractControllerFactory::class,
		],
	],
	
	'router' => [
		'router_class' => TreeRouteStack::class,
		'routes' => [
			'application' => [
				'type' => Segment::class,
				'options' => [
					'route' => '/[:controller[/:action[/:id]]]',
					'constraints'=> [
						'controller' => '[a-zA-Z][a-zA-Z0-9_-]+',
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
					],
					'defaults' => [
						'__NAMESPACE__' => 'Application\Controller',
						'controller' => 'track',
						'action' => 'index',
					],
				],
			],
		],
	],

	'service_manager' => [
		'factories' => [
			'Session\Config' => 'Zend\Session\Service\SessionConfigFactory',
			'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
			\Doctrine\ORM\EntityManager::class => \ContainerInteropDoctrine\EntityManagerFactory::class,
			\Doctrine\ORM\Tools\SchemaTool::class => Service\SchemaToolFactory::class,
			Model\TrackRepository::class => Model\TrackRepositoryFactory::class,
			Model\InterpretRepository::class => Model\InterpretRepositoryFactory::class,
			Model\GenreRepository::class => Model\GenreRepositoryFactory::class,
			Model\TagTypeRepository::class => Model\TagTypeRepositoryFactory::class,
			Model\TagRepository::class => Model\TagRepositoryFactory::class,
			Model\LinkRepository::class => Model\LinkRepositoryFactory::class,
		],
		'aliases' => [
			'translator' => 'MvcTranslator',
		],
	],

	'view_manager' => [
		'display_not_found_reason' => true,
		'display_exceptions' => true,
		'doctype' => 'HTML5',
		'not_found_template' => 'error/404',
		'exception_template' => 'error/index',
		'template_path_stack' => [
			APPLICATION_MODULE_ROOT . '/view',
		],
		'strategies' => array(
			'ViewJsonStrategy',
		),
	],

	'translator' => [
		'locale' => 'en_US',
		'translation_file_patterns' => [
			[
				'type' => 'phparray',
				'base_dir' => APPLICATION_MODULE_ROOT . '/language',
				'pattern' => '%s.php',
			],
		],
	],
	
	'doctrine' => [
		'connection' => [
			'orm_default' => [
				'params' => [
					'url' => 'mysql://root:root@localhost/ng-tracks',
				],
			],
		],
		'driver' => [
			'orm_default' => [
				'class' => \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain::class,
				'drivers' => [
					'Application\Model' => 'ApplicationDriver',
				],
			],
			'ApplicationDriver' => [
				'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => [__DIR__ . '/../src/Application/Model']
			],
		],
	],

	'session_config' => [
		'save_path' => APPLICATION_ROOT . '/data/cache',
		'name' => 'ZF3_SESSION',
	],
];
