# Repman Composer Plugin

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)
[![buddy branch](https://app.buddy.works/repman/composer-plugin/repository/branch/master/badge.svg?token=dbd28b3ece0d16aba095b8a33d0893d15f0403fbcc285a2a1a175cc77f7c94a8 "buddy branch")](https://app.buddy.works/repman/composer-plugin/repository/branch/master)
![License](https://img.shields.io/github/license/repman-io/composer-plugin)

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
