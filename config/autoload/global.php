<?php

return [
    'service_manager' => [
        'factories' => [
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ],
    ],
	/*'doctrine' => [
		'connection' => [
			'orm_default' => [
				'params' => [
					'url' => 'mysql://root:root@localhost/cdcfreiburg',
				],
			],
		],
	],*/
];
