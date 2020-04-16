# Repman Composer Plugin

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)
[![Latest Stable Version](https://poser.pugx.org/repman-io/composer-plugin/v/stable)](https://packagist.org/packages/repman-io/composer-plugin)
[![buddy branch](https://app.buddy.works/repman/composer-plugin/repository/branch/master/badge.svg?token=dbd28b3ece0d16aba095b8a33d0893d15f0403fbcc285a2a1a175cc77f7c94a8 "buddy branch")](https://app.buddy.works/repman/composer-plugin/repository/branch/master)
[![Total Downloads](https://poser.pugx.org/repman-io/composer-plugin/downloads)](https://packagist.org/packages/repman-io/composer-plugin)
![License](https://img.shields.io/github/license/repman-io/composer-plugin)

[Composer](https://getcomposer.org/) plugin for [Repman - PHP Repository Manager](https://repman.io/proxy). Adds a mirror url for all your dependencies without need to update `composer.lock` file.

## Usage

One line install, and you are ready to go:

```shell script
composer global require repman-io/composer-plugin
```

### Self-hosted Repman server

You can use this plugin even with [self-hosted Repman](https://repman.io/self-hosted) instance. Add this config to your `composer.json` file:

```json
    "extra": {
        "repman": {
            "url": "https://repman.your.company/"
        }
    }
```

---

made with ❤️ by [Buddy](https://buddy.works)
