<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'kms',
    'name' => 'Knowledge Modeling System',
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
                '/' => 'main/default/index',
                'contact' => 'main/default/contact',
                'sing-in' => 'main/default/sing-in',
                '<_dgm:(my-diagrams|diagrams|create)>' => 'main/default/<_dgm>',
                '<_dgm:(view|update|delete|import|upload-ontology|creation-template)>/<id:\d+>' => 'main/default/<_dgm>',
                '/tree-diagrams/<_eet:(edit-level|edit-event|edit-mechanism|delete-event|delete-mechanism|add-relationship|delete-relationship|add-parameter|edit-parameter|delete-parameter|add-event-comment|edit-event-comment|delete-event-comment|add-level-comment|edit-level-comment|delete-level-comment|save-indent)>' =>
                    'eete/tree-diagrams/<_eet>',
                '/tree-diagrams/<_eet:(visual-diagram|add-level|add-event|add-mechanism|delete-level|correctness|move-level|upload-ontology|convert-ontology)>/<id:\d+>' =>
                    'eete/tree-diagrams/<_eet>',
                '/state-transition-diagrams/<_std:(visual-diagram|add-state)>/<id:\d+>' =>
                    'stde/state-transition-diagrams/<_std>',
                '/state-transition-diagrams/<_std:(edit-state|delete-state|add-state-property|edit-state-property|delete-state-property|add-transition|edit-transition|delete-transition|add-transition-property|edit-transition-property|delete-transition-property|save-indent)>' =>
                    'stde/state-transition-diagrams/<_std>',
                '/user/<_usr:(list|create)>' => 'main/user/<_usr>',
                '/user/<_usr:(view|update|delete|profile|update-profile|change-password)>/<id:\d+>' =>
                    'main/user/<_usr>',
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