## Laravel Security Component

### Very alpha, API might change!

This packages tries to integrate Symfony Security Core in Laravel, mainly to use the Voters to check acces to roles/objects. See http://symfony.com/doc/current/components/security/authorization.html


### Install
Add this package to your composer.json and `composer update`

    "barryvdh/laravel-security": "dev-master"

After updating, add the ServiceProvider to ServiceProvider array in app/config/app.php

    'Barryvdh\Security\SecurityServiceProvider'

You can optionally add the Facade as well, to provide faster access to the Security component.

    'Security' => 'Barryvdh\Security\Facade',


### Configure
You can publish the config to change the strategy and add your own Role Hierarchy, to configure which roles inherit from each other.

     $ php artisan config:publish barryvdh/laravel-security

    //app/config/packages/barryvdh/laravel-security/config.php
    'role_hierarchy' => array(
           'ROLE_ADMIN' => array('ROLE_USER'),
           'ROLE_SUPER_ADMIN' => array('ROLE_ADMIN', 'ROLE_ALLOWED_TO_SWITCH')
     )

### Voters
By default, only 2 Voters are included:
 - AuthVoter, check if a user is autenticated (`IS_AUTHENTICATED`)
 - RoleHierarchyVoter: Check a user for a role, using the hierarchy in the config. (`ROLE_ADMIN`, `ROLE_EDITOR` etc)

To use roles, add a function getRoles() to your User model, which returns an array of Role strings

    public function roles(){
        return $this->belongsToMany('Role');
    }
    public function getRoles(){
        return $this->roles()->lists('name');
    }

You can add voters by extending $app['security.voters'] or using the facade:

    Facade:addVoter(new MyVoter());

Voters have to implement VoterInterface (https://github.com/symfony/Security/blob/master/Core/Authorization/Voter/VoterInterface.php).
You can define which attributes (ie. ROLE_ADMIN, IS_AUTHENTICATED, EDIT etc) and which objects the voter can handle.
The voter will be called to vote on an attribute (and possibly an object) and allow, deny or abstain access.
Based on the strategy, the final decision is made based on the votes. (By default, 1 allow is enough)

You can access the User object with $token->getUser();
For an example, see http://symfony.com/doc/current/cookbook/security/voters.html

### Checking access
You can check access using to IoC Container, the facade and a helper function:

    App::make('security')->isGranted('ROLE_ADMIN');
    Security::isGranted('EDIT', $post);
    is_granted('IS_AUTHENTICATED');

The first argument is the attribute you want to check, the second is an optional object, on which you want to check the access.
For example, you can write a Voter to check if the current user can edit a comment, based on his ownership on that object or his role.
