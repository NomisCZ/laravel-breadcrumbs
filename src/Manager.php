<?php

namespace NomisCZ\Breadcrumbs;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Arr;

class Manager
{
    /**
     * The view factory.
     *
     * @var Factory
     */
    protected $view;

    /**
     * The config repository.
     *
     * @var Repository
     */
    protected $config;

    /**
     * The breadcrumb generator.
     *
     * @var Generator
     */
    protected $generator;

    /**
     * Create the instance of the manager.
     *
     * @param Factory $view
     * @param Repository $config
     * @param Generator $generator
     * @return void
     */
    public function __construct(Factory $view, Repository $config, Generator $generator)
    {
        $this->view = $view;
        $this->config = $config;
        $this->generator = $generator;
    }

    /**
     * Register a breadcrumb definition by passing it off to the registrar.
     *
     * @param string $route
     * @param Closure $definition
     * @return void
     */
    public function for(string $route, Closure $definition)
    {
        $this->generator->register($route, $definition);
    }

    /**
     * Render the breadcrumbs as an HTML string
     *
     * @param  array  $parameters
     * @return  Htmlable
     */
    public function render($parameters = null): ?Htmlable
    {
        $parameters = Arr::wrap($parameters);

        if ($breadcrumbs = $this->generator->generate($parameters)) {
            return $this->view->make($this->config->get('breadcrumbs.view'), compact('breadcrumbs'));
        }
    }
}
