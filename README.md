# Repman Composer Plugin

[Composer](https://getcomposer.org/) plugin for [Repman - PHP Repository Manager](https://github.com/buddy-works/repman). Add mirror url for all your dependencies without need to update `composer.lock` file.

## Usage

One line install and you are ready to go:

```shell script
composer global require buddy-works/repman-composer-plugin
```

### Custom Repman Server

You can use this plugin even with custom Repman instance. Add this config to your `composer.json` file:

```json
    "extra": {
        "repman": {
            "url": "https://repman.your.company/"
        }
    }
```
