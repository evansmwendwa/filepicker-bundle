# filepicker-bundle
Symfony Bundle for adding a file picker component to form inputs

### Warning: This package is at a very early stage of development. Use at your own risk


Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require evansmwendwa/filepicker-bundle "dev-master"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Evans\FilepickerBundle\EvansFilepickerBundle(),
        );

        // ...
    }

    // ...
}
```

Step 3: Create config parameters
--------------------------------

```yml
# app/config/config.yml
evans_filepicker:
    uploads_destination: '/uploads/images'
    allow_uploads: true
    allowed_mimes: ['image/jpeg','image/png','image/gif']
    group_files: false
```

Step 4: Register form theme in twig
-----------------------------------
```
# Twig Configuration
twig:
    form_themes:
        - EvansFilepickerBundle::form/fields.html.twig
```

Step 5: Load bundle assets in your Twig Template
------------------------------------------------

The bundle provides two twig functions for loading the necessary HTML, CSS and JS.
Add these functions somewhere in your twig template. Can be base in you base template if necessary

```
# loads necessary html and js files
{{ file_picker_init() }}
```

```
# loads necessary css files
{{ file_picker_init_css() }}
```

Step 6: Register FilepickerBundle Routes
----------------------------------------

```twig
# app/config/routing.yml

evans_filepicker:
    resource: "@EvansFilepickerBundle/Resources/config/routing.yml"
    prefix:   /
```

## Using the built in CKE Editor Plugin

`Instructions coming soon`

## Using File picker bundle with Symfony EasyAdminBundle

`Instructions coming soon`
