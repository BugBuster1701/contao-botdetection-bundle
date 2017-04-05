# Installation von Contao BotDetection Bundle

Es gibt zwei Arten der Installation.

* mit dem Contao-Manger, nur für die Contao Managed-Editon
* über die Kommandozeile, für Contao Standard-Edition und Managed-Editon


## Installation über Contao-Manager

* Suche das Paket: `bugbuster/contao-botdetection-bundle`
* Installation der Erweiterung


## Installation über die Kommandozeile

### Installation in einer Contao Managed-Edition

Installation in einer Composer-basierenden Contao 4.3+ Managed-Edition:

* `composer require "bugbuster/contao-botdetection-bundle"`


### Installation in einer Contao Standard-Edition

Installation in einer Composer-basierenden Contao 4.2+ Standard-Edition:

* `composer require "bugbuster/contao-botdetection-bundle"`

Einfügen in `app/AppKernel.php` folgende Zeile am Ende des Array `$bundles`:

`new BugBuster\BotdetectionBundle\BugBusterBotdetectionBundle(),`

Cache leeren und neu anlegen lassen:

* `app/console cache:clear --env=prod`
* `app/console cache:warmup -e prod`

