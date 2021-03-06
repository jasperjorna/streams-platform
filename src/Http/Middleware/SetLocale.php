<?php namespace Anomaly\Streams\Platform\Http\Middleware;

use Anomaly\Streams\Platform\Support\Locale;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

/**
 * Class SetLocale
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class SetLocale
{

    /**
     * The config config.
     *
     * @var Repository
     */
    protected $config;

    /**
     * The locale helper.
     *
     * @var Locale
     */
    protected $locale;

    /**
     * The redirect utility.
     *
     * @var Redirector
     */
    protected $redirect;

    /**
     * The laravel application.
     *
     * @var Application
     */
    protected $application;

    /**
     * Create a new SetLocale instance.
     *
     * @param Locale      $locale
     * @param Repository  $config
     * @param Redirector  $redirect
     * @param Application $application
     */
    public function __construct(Locale $locale, Repository $config, Redirector $redirect, Application $application)
    {
        $this->config      = $config;
        $this->locale      = $locale;
        $this->redirect    = $redirect;
        $this->application = $application;
    }

    /**
     * Look for locale=LOCALE in the query string.
     *
     * @param  Request  $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (defined('LOCALE')) {
            return $next($request);
        }

        if ($locale = $request->get('_locale')) {
            if ($locale) {
                $request->session()->put('_locale', $locale);
            } else {
                $request->session()->remove('_locale');
            }

            return $this->redirect->back();
        }

        if ($locale = $request->session()->get('_locale')) {

            $this->application->setLocale($locale);

            Carbon::setLocale($locale);

            setlocale(LC_TIME, $this->locale->full($locale));

            $this->config->set('_locale', $locale);
        }

        if (!$locale) {

            $this->application->setLocale($this->config->get('streams::locales.default'));

            Carbon::setLocale($this->config->get('streams::locales.default'));

            setlocale(LC_TIME, $this->locale->full($this->config->get('streams::locales.default')));
        }

        return $next($request);
    }
}
