<?php namespace Vinelab\Cdn;

/**
 * @author Mahmoud Zalt <inbox@mahmoudzalt.com>
 */

use Illuminate\Support\ServiceProvider;


class CdnServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('vinelab/cdn');

    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

        // implementation bindings:
        //-------------------------
        $this->app->bind(
            'Vinelab\Cdn\Contracts\CdnInterface',
            'Vinelab\Cdn\Cdn'
        );

        $this->app->bind(
            'Vinelab\Cdn\Providers\Contracts\ProviderInterface',
            'Vinelab\Cdn\Providers\AwsS3Provider'
        );

        $this->app->bind(
            'Vinelab\Cdn\Contracts\AssetHolderInterface',
            'Vinelab\Cdn\AssetHolder'
        );

        $this->app->bind(
            'Vinelab\Cdn\Contracts\FinderInterface',
            'Vinelab\Cdn\Finder'
        );

        $this->app->bind(
            'Vinelab\Cdn\Contracts\ProviderFactoryInterface',
            'Vinelab\Cdn\ProviderFactory'
        );

        $this->app->bind(
            'Vinelab\Cdn\Contracts\CdnFacadeInterface',
            'Vinelab\Cdn\CdnFacade'
        );




        // register the commands:
        //-----------------------
        $this->app['cdn.push'] = $this->app->share(function()
            {
                return  $this->app->make('Vinelab\Cdn\Commands\PushCommand');
            });

        $this->commands('cdn.push');




        // facade bindings:
        //-----------------

        // Register 'CdnFacade' instance container to our CdnFacade object
        $this->app['cdn'] = $this->app->share(function()
            {
                return $this->app->make('Vinelab\Cdn\CdnFacade');
            });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        $this->app->booting(function()
            {
                $loader = \Illuminate\Foundation\AliasLoader::getInstance();
                $loader->alias('Cdn', 'Vinelab\Cdn\Facades\CdnFacadeAccessor');
            });



    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
