Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require coka/notifier-client-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require coka/notifier-client-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Oka\Notifier\ClientBundle\OkaNotifierClientBundle::class => ['all' => true],
];
```

### Step 3: Configure the Bundle

Add the following configuration in the file `config/packages/oka_notifier_client.yaml`.

```yaml
# config/packages/oka_notifier_client.yaml
oka_notifier_client:
    service_name: notifier
    logger_id: logger
```
