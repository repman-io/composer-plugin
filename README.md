# Repman Composer Plugin

**WIP: currently being developed, do not use on production**

[Composer](https://getcomposer.org/) plugin for [Repman - PHP Repository Manager](https://repman.io/proxy). Add mirror url for all your dependencies without need to update `composer.lock` file.

## Usage

One line install and you are ready to go:

```shell script
composer global require repman-io/composer-plugin
```

### Custom Repman Server

You can use this plugin even with custom [standalone Repman](https://repman.io/standalone) instance. Add this config to your `composer.json` file:

```json
    "extra": {
        "repman": {
            "url": "https://repman.your.company/"
        }
    }
```
