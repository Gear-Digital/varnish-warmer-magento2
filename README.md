# Magento2 Varnish Cache Warmer 

Magento2 module for handling Varnish Cache cleaning and regenerating using admin panel
and CLI. It uses multiple threads to run the commands to minimize the time required 
to run.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

* Magento 2.2
* PHP 7.1

### Installing

#### Download the module

##### Using composer (suggested)

Add the repository to your composer.json
```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/lizardmedia/varnish-warmer-magento2"
    }
]
```
and run

```
composer require lizardmedia/varnish-warmer-magento2
```

##### Downloading ZIP

Download a ZIP version of the module and unpack it into your project into
```
app/code/LizardMedia/VarnishWarmer
```
If you use ZIP file you will need to install the dependencies of the module
manually
```
composer require stil/curl-easy:^1.1
```

#### Install the module

Run this command
```
bin/magento module:enable LizardMedia_VarnishWarmer
bin/magento setup:upgrade
```

## Usage

#### Admin panel

The commands can be run in backround from admin panel using ``Lizard Media Varnish Warmer``
menu tab.

#### CLI

The commands can be run using Magento built-in CLI:
* ``lm-varnish:cache-purge-homepage`` - purges and regenerates homepage
* ``lm-varnish:cache-purge-general`` - purges and regenerates homepage and categories
* ``lm-varnish:cache-purge-wildcard`` - purges * and regenerates homepage, categories and products
* ``lm-varnish:cache-purge-all`` - purges and regenerates homepage, categories and products
* ``lm-varnish:cache-purge-force`` - purges * without regenerating. Ommits lock check
* ``lm-varnish:cache-purge-products`` - purges and regenerates products 
* ``lm-varnish:cache-refresh-url`` - purges and regenerates single URL given as argument

## For developers

The number of threads used for purging and regenerating Varnish cache can be customized
in the admin panel. The max number of processes is specified in 
``LizardMedia\VarnishWarmer\Model\Config\GeneralConfigProvider``
If you have a powerful server you can modify those numbers to use more resources.

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/lizardmedia/varnish-warmer-magento2/tags). 

## Authors

* **Maciej Sławik** - *Initial work* - [Lizard Media](https://github.com/maciejslawik)

See also the list of [contributors](https://github.com/lizardmedia/varnish-warmer-magento2/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## To do

* Add running CLI commands in background
* Remove CacheCleaner helper
