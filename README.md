# Yii2 telegram bot component on Longman Telegram Bot
## Install by composer
composer require evgeralt/yii2-telegram-bot
## Or add this code into require section of your composer.json and then call composer update in console
"evgeralt/yii2-telegram-bot": "*"
## Usage
In configuration file do
```php
<?php
  'components'  =>  [
        'telegram' => [
            'class' => \evgeralt\yii2telegram\TelegramBot::class,
            'api_key' => '',
            'bot_name' => '',
            'webhook' => '',
        ],
  ],
```
