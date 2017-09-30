YAML Gettext Extractor [![Build Status](https://secure.travis-ci.org/ModelTech/YAML-Gettext-Extractor.svg?branch=master)](http://travis-ci.org/ModelTech/YAML-Gettext-Extractor)
======================

The YAML Gettext Extractor is [Poedit](http://www.poedit.net/download.php)
friendly tool which extracts translations from YAML file.

## Installation

### Manual

#### Local

Download the ``yml-gettext-extractor.phar`` file and store it somewhere on your computer.

#### Global

You can run these commands to easily access ``yml-gettext-extractor`` from anywhere on
your system:

```bash
$ sudo wget https://github.com/ModelTech/YAML-Gettext-Extractor/releases/download/1.0.0/yml-gettext-extractor.phar -O /usr/local/bin/yml-gettext-extractor
$ sudo chmod a+x /usr/local/bin/yml-gettext-extractor
```
Then, just run ``yml-gettext-extractor``.

### Composer

#### Local

```bash
$ composer require ModelTech/yml-gettext-extractor
```

#### Global

```bash
$ composer global require ModelTech/yml-gettext-extractor
```

Make sure you have ``~/.composer/vendor/bin`` in your ``PATH`` and
you're good to go:

```bash
$ export PATH="$PATH:$HOME/.composer/vendor/bin"
```
Don't forget to add this line in your `.bashrc` file if you want to keep this change after reboot.

## Setup

By default, Poedit does not have the ability to parse YML files.
This can be resolved by adding an additional parser (Edit > Preferences > Parsers)
with the following options:

- Language: `YAML`
- List of extensions: `*.yml`
- Invocation:
    - Parser command: `<project>/vendor/bin/yml-gettext-extractor  --out %o --keys %K --files %F` (replace `<project>` with absolute path to your project)
    - An item in keyword list: `-k%k`
    - An item in input file list: `%f` 

 
Now you can update your catalog and Poedit will synchronize it with your yml
config files.
 

