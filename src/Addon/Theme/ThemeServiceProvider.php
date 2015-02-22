<?php namespace Anomaly\Streams\Platform\Addon\Theme;

use Anomaly\Streams\Platform\Addon\Theme\Command\DetectActiveTheme;
use Anomaly\Streams\Platform\Addon\Theme\Command\DetectDefaultTheme;
use Anomaly\Streams\Platform\Addon\Theme\Command\RegisterListeners;
use Anomaly\Streams\Platform\Addon\Theme\Command\RegisterThemes;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Support\ServiceProvider;

/**
 * Class ThemeServiceProvider
 *
 * @link    http://anomaly.is/streams-platform
 * @author  AnomalyLabs, Inc. <hello@anomaly.is>
 * @author  Ryan Thompson <ryan@anomaly.is>
 * @package Anomaly\Streams\Platform\Addon\Theme
 */
class ThemeServiceProvider extends ServiceProvider
{

    use DispatchesCommands;

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->dispatch(new RegisterThemes());

        if (app('Anomaly\Streams\Platform\Application\Application')->isInstalled()) {
            $this->dispatch(new DetectActiveTheme());
        } else {
            $this->dispatch(new DetectDefaultTheme());
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'Anomaly\Streams\Platform\Addon\Theme\ThemeCollection',
            'Anomaly\Streams\Platform\Addon\Theme\ThemeCollection'
        );

        $this->app->register('Anomaly\Streams\Platform\Addon\Theme\ThemeEventProvider');
    }
}
