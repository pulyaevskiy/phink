Phink
=====

Phink is a PHP library intended to help developers when working with Git from PHP.

The Phink has started as private project and currently is in early development stage so it doesn't do much. 
However there is a bunch of already implemented git commands like: init, clone, add, checkout, pull.

More to be added soon.

Usage
=====

You can require Phink as a dependency in composer.json of your project. 
Currently there is no stable release of the library available yet. However you can refer to the master branch using version 1.0.* (thanks to Composer branch-alias feature).
So when actual 1.0 version will be released you won't need to change anything on your side. Example:
```json
{
  "require": {
    "phink/phink": "1.0.*"
  }
}
```

Then just start with creating instance of Phink\Repository class:
```php
use Phink\Repository;

$repo = new Repository($pathToRepository);

// If you need to initialize new repository:
$repo
  ->init()
  ->execute();
  
// Stage files:
$repo
  ->add()
  ->filePattern('.')
  ->execute();
  
// Pull changes:
$repo->pull()
  ->from('origin', 'master')
  ->execute();
  
// And so on...
```

License
=====

This library is under MIT license. Please see the complete license in the LICENSE file provided with library source code.
