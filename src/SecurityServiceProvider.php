<?php namespace Barryvdh\Security;

use Illuminate\Support\ServiceProvider;

use Barryvdh\Security\Authentication\AuthenticationManager;
use Barryvdh\Security\Authorization\Voter\AuthVoter;
use Barryvdh\Security\Authentication\Token\LaravelToken;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
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

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/security.php' => config_path('security.php'),
        ], 'config');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->mergeConfigFrom(
            __DIR__.'/../config/security.php', 'security'
        );

        $app = $this->app;

        $app['security.role_hierarchy'] = $app['config']->get('security.role_hierarchy', array());
        $app['security.strategy'] = $app['config']->get('security.strategy', 'affirmative');

        $app['security'] = $app->share(function ($app) {
                // Deprecated. Use security.authorization_checker instead.
                $security = new SecurityContext($app['security.authentication_manager'], $app['security.access_manager']);
                $security->setToken(new LaravelToken($app['auth']->user()));
                return $security;
            });

        $app['security.token_storage'] = $app->share(function($app) {
                $tokenStorage = new TokenStorage();
                $tokenStorage->setToken(new LaravelToken($app['auth']->user()));
                return $tokenStorage;
            });

        $app['security.authorization_checker'] = $app->share(function ($app) {
                return new AuthorizationChecker($app['security.token_storage'], $app['security.authentication_manager'], $app['security.access_manager']);
            });

        $app['security.authentication_manager'] = $app->share(function ($app) {
                return new AuthenticationManager();
            });

        $app['security.access_manager'] = $app->share(function ($app) {
                return new AccessDecisionManager($app['security.voters'], $app['security.strategy']);
            });

        $app->bind('Symfony\Component\Security\Core\Role\RoleHierarchyInterface', function($app) {
                return new RoleHierarchy($app['security.role_hierarchy']);
            });

        $app['security.voters'] = $app->share(function ($app) {
                return array_map(function($voter) use ($app) {
                    return $app->make($voter);
                }, $app['config']->get('security.voters'));
            });

        //Listener for Login event
        $app['events']->listen('auth.login', function($user) use($app){
                $app['security.token_storage']->setToken(new LaravelToken($user));
            });

        $app['events']->listen('auth.logout', function() use($app){
                $app['security.token_storage']->setToken(new LaravelToken(null));
            });
	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('security', 'security.role_hierarchy' , 'security.authentication_manager', 'security.access_manager', 'security.voters', 'security.authorization_checker' );
	}

}
