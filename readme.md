Notifications for Yii2
======================

[![License](https://img.shields.io/github/license/kaswell/yii-notifications?style=flat-square)](license.md)
[![Total size](https://img.shields.io/github/repo-size/kaswell/yii-notifications?style=flat-square)](https://packagist.org/packages/kaswell/laravel-boxapi)
[![Last Version](https://img.shields.io/github/v/release/kaswell/yii-notifications?style=flat-square)](https://packagist.org/packages/kaswell/laravel-boxapi)

Notifier is used as an application component and configured in the application configuration like the following:

```
[
   'components' => [
       'notifier' => [
           'class' => \kaswell\notification\Notifier::class,
           'channels' => [
               'telegram' => [
                    'class' => \kaswell\notification\channels\TelegramChannel::class,
                    'botToken' => '...'
                ],
               'mail' => [
                   'class' => \kaswell\notification\channels\MailChannel::class,
                   'from' => 'no-reply@example.com'
               ],
               'database' => [
                    'class' => \kaswell\notification\channels\ActiveRecordChannel::class
               ]
           ],
       ],
   ],
]
```

Add NotificationBehavior to any model or active record

```php 
public function behaviors()
{
    return [
        ...
        [
            'class' => ActiveRecordBehavior::class,
            'excludedAttributes' => ['updated_at'],
        ],
        ...
    ];
}
```


Migrations

    yii migrate --migrationPath=@vendor/kaswell/yii-notifications/src/migrations

or

```
'controllerMap' => [
    ...
    'migrate' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationNamespaces' => [
            'kaswell\notification\migrations',
        ],
    ],
    ...
],
```