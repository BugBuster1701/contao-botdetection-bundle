# Installation of Contao BotDetection Bundle

There are two types of installation.

* with the Contao-Manger, only for Contao Managed-Editon
* via the command line, for Contao Standard-Edition and Managed-Editon


## Installation with Contao-Manager

* search for package: `bugbuster/contao-botdetection-bundle`
* install the package


## Installation via command line

### Installation for Contao Managed-Edition

Installation in a Composer-based Contao 4.3+ Managed-Edition:

* `composer require "bugbuster/contao-botdetection-bundle"`


### Installation for Contao Standard-Edition

Installation in a Composer-based Contao 4.2+ Standard-Edition

* `composer require "bugbuster/contao-botdetection-bundle"`

Add in `app/AppKernel.php` following line at the end of the `$bundles` array.

`new BugBuster\BotdetectionBundle\BugBusterBotdetectionBundle(),`

Clears the cache and warms up an empty cache:

* `app/console cache:clear --env=prod`
* `app/console cache:warmup -e prod`

