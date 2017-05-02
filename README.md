# Stydnet Factory Package

This packages allows you to build model factories
 for Laravel 5.4 using classes and methods instead of closures.
 
## How to install

Install by running composer `require styde/factory:"dev-master" --dev`
 or adding `"styde/factory": "dev-master"` to the dev dependencies (`require-dev`) in the project's composer.json file
  and then running composer update. 

Then create a "factory-classes" directory inside "database"
 and add the following to the "autoload-dev" section in your composer.json file:

```
      "autoload-dev": {
          "classmap": [
              "database/factory-classes",
```

And then execute `composer dump-autoload` in the console.
 
**Note**: you can put the factory classes anywhere you want 
 and of course you can also use PSR-4 if you want to.
 
**Warning**: Laravel loads the database/factories/ multiple times during the test cycle.
 This will cause a conflict since you cannot override classes. So please don't put
  the factory classes in the database/factories directory, unless you are completely sure you are not using the `factory` helper.
  
## Creating Factory Classes:

The factory classes have the following structure:

```
<?php

class UserFactory extends Styde\Factory\Factory
{
    protected $model = 'App\User';

    public function data()
    {
        return [
            /***/
        ];
    }
}
```

You can name the factory class whatever you like,
but you need to extend `Styde\Factory\Factory`

You also need to define the `$model` property
and assign it the name of the model you want to create, (example: `App\User`). 

And you need to add a `data()` method that will return
 an array with the default attributes for this model factory:
 
 Example:
 
```
     public function data()
     {
         return [
             'first_name' => $this->firstName,
             'last_name' => $this->lastName,
             'username' => $this->unique()->userName,
             'email' => $this->unique()->safeEmail,
             'remember_token' => str_random(10),
         ];
     }
```
  
Notice in the case `$this->` is a proxy of `$this->faker->`

### States:

Since we are using classes now, states become simple methods inside the factory class:

```
    public function stateDelinquent()
    {
        return ['account_status' => 'delinquent'];
    }
```

Note these methods need to be prefixed with the "state" 
 and need to return the corresponding attributes.
 
### Usage:

With factory classe **you won't use the factory helper**, instead you can write this:

`UserFactory::create()` in order to create a new user and register it in the database.

#### Other examples:

* `UserFactory::make()` creates a new user without persisting it in the database.

* `UserFactory::times(3)->create()` creates 3 users and persist them in the database.

* `UserFactory::delinquent()->create()` creates a user with delinquent status (following the previous example about states).

**This package is in development:** more tests, features and a stable version will be added later.

If you want to collaborate please send a pull request or report an issue here in GitHub.

