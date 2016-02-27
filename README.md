# Freebox DNS auto update

## Introduction

Suite aux nombreux blocages des DNS par differents acteurs, les serveurs DNS des principaux FAI mentent quand on leur demande accès a certains sites.
Afin de contourner ces bloquages, il est possible de configurer sa freebox pour utiliser d'autres serveurs DNS.

Ce petit script vous permettra de changer régulièrement de serveur DNS en vous basant sur les serveurs OpenNic les plus proches.

## Installation

Après avoir récupéré le script, il vous faudra utiliser `composer` pour rapatrier les dépendences.

### Récupération de composer

```bash
curl -sS https://getcomposer.org/installer | php
```

### Installation des dépendances

```bash
php composer.phar install
```

## Utilisation

Une fois le script et ses dépendances installées,

1. Lancez le script `./freebox_dns_changer.php`
2. Autoriser l'application a acceder a votre Freebox (Cela se passe sur le cadrant digital de votre box)
3. Donner les droits a l'application d'acceder a vos parametres freebox (via l'interface web de la freeboite)
