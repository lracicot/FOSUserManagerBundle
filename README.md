# User Manager Bundle for FOSUserBundle

This bundle is an admin panel for the users from the [Friends Of Symfony User Bundle](https://github.com/FriendsOfSymfony/FOSUserBundle). It allow to perform basic CRUD operations on the users.

## Install

1. Install the FOSUserBundle
2. Download lracicotFOSUserManagerBundle using composer
3. Enable the Bundle
4. Import lracicotFOSUserManagerBundle routing files

### Step 1: Install the FOSUserBundle

Follow the instructions from the [FOSUserBundle documentation](https://symfony.com/doc/master/bundles/FOSUserBundle/index.html).

### Step 2: Download lracicotFOSUserManagerBundle using composer

Require the bundle using composer:

```shell
$ composer require lracicot/fosusermanager
```

### Step 3: Register the bundle

Enable the bundle in the kernel:


```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new lracicot\FOSUserManagerBundle\lracicotFOSUserManagerBundle(),
        // ...
    );
}
```

### Step 4: Import lracicotFOSUserManagerBundle routing files

```yaml
# app/config/routing.yml
lracicot_fos_user_manager:
    resource: "@lracicotFOSUserManagerBundle/Resources/config/routing.xml"
    prefix:   /admin/user/
```

You can change the prefix for whatever you want to match your application configurations.

## Contributing

Any code review, suggestions or pull requests would be appreciated. Make sure that your changes have tests.
