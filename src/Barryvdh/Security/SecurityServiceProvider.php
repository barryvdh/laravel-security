<?php namespace Barryvdh\Security;

use Illuminate\Support\ServiceProvider;

use Barryvdh\Security\Authentication\AuthenticationManager;
use Barryvdh\Security\Authorization\Voter\AuthVoter;
use Barryvdh\Security\Authentication\Token\LaravelToken;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Role\RoleHierarchy;


class SecurityServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $app = $this->app;

        $app['security.role_hierarchy'] = array();
        $app['security.access_rules'] = array();

        $app['security'] = $app->share(function ($app) {
                $security = new SecurityContext($app['security.authentication_manager'], $app['security.access_manager']);
                $security->setToken(new LaravelToken($app['auth']->user()));
                return $security;
            });

        $app['security.authentication_manager'] = $app->share(function ($app) {
                return  new AuthenticationManager();
            });

        $app['security.access_manager'] = $app->share(function ($app) {
                return new AccessDecisionManager($app['security.voters']);
            });

        $app['security.voters'] = $app->share(function ($app) {
                return array(
                    new RoleHierarchyVoter(new RoleHierarchy($app['security.role_hierarchy'])),
                    new AuthVoter(),
                );
            });

        //Listener for Login event
        $app['events']->listen('auth.login', function($user) use($app){
                $app['security']->setToken(new LaravelToken($user));
            });
	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('security', 'security.role_hierarchy', 'security.access_rules', 'security.authentication_manager', 'security.access_manager', 'security.voters' );
	}

}