# PHP Telegram Bot for controlling the channel

> :construction: Work In Progress :construction:

❗Attention❗ this bot uses [PHP Telegram Bot][core-github] library and [PHP example bot][example-bot] as template.

The main purpose of this bot is to check pictures in channels by hash and if 2 pictures are same delete last one and replace it with picture that was posted earlier.

## 0. Making your own instance of a bot

To start off, you can clone this repository using git:

```bash
$ git clone https://github.com/SnakeOPM/tg_channel_controller.git
```

Unzip the files to the root of your project folder.

**configure the bot as indicated in [dotenv.example][dotenv-example] and rename it to .env**

---


Now you can install all dependencies using [composer]:
```bash
$ composer install
```
---
provide hook info using hook.php or [manager.php][bot-manager-github]

**For a more detailed installation please use [webhook installation][core-github-webhook-installation]**

## To be continued!

[core-github]: https://github.com/php-telegram-bot/core "php-telegram-bot/core"
[core-github-webhook-installation]: https://github.com/php-telegram-bot/core#webhook-installation "php-telegram-bot/core - webhook install"
[core-readme-github]: https://github.com/php-telegram-bot/core#readme "PHP Telegram Bot - README"
[bot-manager-github]: https://github.com/php-telegram-bot/telegram-bot-manager "php-telegram-bot/telegram-bot-manager"
[bot-manager-readme-github]: https://github.com/php-telegram-bot/telegram-bot-manager#readme "PHP Telegram Bot Manager - README"
[composer]: https://getcomposer.org/ "Composer"
[example-bot]: https://github.com/php-telegram-bot/example-bot "example-bot"
[dotenv-example]: .env.example "dotenv.example"
