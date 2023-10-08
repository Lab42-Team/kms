<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'kms',
    'name' => 'KMS',
    'defaultRoute' => 'main/default/index',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],

    // all site modules
    'modules' => [
        'main' => [
            'class' => 'app\modules\main\Module',
        ],
        'eete'=> [
            'class' => 'app\modules\eete\Module',
        ],
        'stde'=> [
            'class' => 'app\modules\stde\Module',
        ],
        'api' => [
            'class' => 'app\modules\api\Module',
        ]
    ],

    'components' => [
        'language' => 'ru-RU',
        'request' => [
            'class' => 'app\components\LangRequest',
            // site root directory
            'baseUrl' => '',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '6jAfjP4dmwn1UymtvH_PZA_TywLtLzga',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'class' => 'app\components\LangUrlManager',
            'rules' => [
                /* Основные роуты системы */
                '/' => 'main/default/index',
                'contact' => 'main/default/contact',
                'sing-in' => 'main/default/sing-in',
                '<_dgm:(my-diagrams|diagrams|create)>' => 'main/default/<_dgm>',
                '<_dgm:(view|update|delete|import|upload-ontology|creation-template|upload-csv)>/<id:\d+>' => 'main/default/<_dgm>',
                /* Роуты для редакторов EETE и STDE */
                '/tree-diagrams/<_eet:(edit-level|edit-event|edit-mechanism|delete-event|delete-mechanism|add-relationship|delete-relationship|add-parameter|edit-parameter|delete-parameter|copy-event|copy-event-to-level|add-event-comment|edit-event-comment|delete-event-comment|add-level-comment|edit-level-comment|delete-level-comment|save-indent)>' =>
                    'eete/tree-diagrams/<_eet>',
                '/tree-diagrams/<_eet:(visual-diagram|add-level|add-event|add-mechanism|delete-level|correctness|move-level|upload-ontology|convert-ontology)>/<id:\d+>' =>
                    'eete/tree-diagrams/<_eet>',
                '/state-transition-diagrams/<_std:(visual-diagram|add-state|add-start|add-end)>/<id:\d+>' =>
                    'stde/state-transition-diagrams/<_std>',
                '/state-transition-diagrams/<_std:(edit-state|delete-state|copy-state|add-state-property|edit-state-property|delete-state-property|add-transition|edit-transition|delete-transition|add-transition-property|edit-transition-property|delete-transition-property|save-indent|delete-start|delete-end|end-connection|start-connection|del-state-connection|save-indent-start-or-end)>' =>
                    'stde/state-transition-diagrams/<_std>',
                /* Роуты для пользователей */
                '/user/<_usr:(list|create)>' => 'main/user/<_usr>',
                '/user/<_usr:(view|update|delete|profile|update-profile|change-password)>/<id:\d+>' =>
                    'main/user/<_usr>',
                /* Роуты для виртуальных ассистентов */
                '/virtual-assistant/<_vas:(list|create)>' => 'main/virtual-assistant/<_vas>',
                '/virtual-assistant/<_vas:(view|update|delete|generate|open-dialogue-model|open-knowledge-base-model|generate-platform|download-json|download-csv|download-json2)>/<id:\d+>' =>'main/virtual-assistant/<_vas>',
                /* Роуты для REST API */
                '/api/get-all-state-transition-diagrams-list' => 'api/api/get-all-state-transition-diagrams-list',
                '/api/get-all-event-tree-diagrams-list' => 'api/api/get-all-event-tree-diagrams-list',
                '/api/export-state-transition-diagram/<id:\d+>' => 'api/api/export-state-transition-diagram',
                '/api/export-event-tree-diagram/<id:\d+>' => 'api/api/export-event-tree-diagram',
                '/api/import-state-transition-diagram/<id:\d+>' => 'api/api/import-state-transition-diagram',
                '/api/import-event-tree-diagram/<id:\d+>' => 'api/api/import-event-tree-diagram',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\main\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['main/default/sing-in'],
        ],
        'errorHandler' => [
            'errorAction' => 'main/default/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'forceTranslation' => true,
                    'sourceLanguage' => 'en-US',
                ],
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;