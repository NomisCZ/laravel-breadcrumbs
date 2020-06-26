<?php

namespace NomisCZ\Breadcrumbs;

use Closure;
use Illuminate\Contracts\Routing\Registrar as Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Generator
{
    /**
     * The router.
     *
     * @var Router
     */
    protected $router;

    /**
     * The breadcrumb registrar.
     *
     * @var Registrar
     */
    protected $registrar;

    /**
     * The breadcrumb trail.
     *
     * @var Collection
     */
    protected $breadcrumbs;

    /**
     * Create a new instance of the generator.
     *
     * @param Router $router
     * @param Registrar $registrar
     * @return void
     */
    public function __construct(Router $router, Registrar $registrar)
    {
        $this->router = $router;
        $this->registrar = $registrar;
        $this->breadcrumbs = new Collection;
    }

    /**
     * Register a definition with the registrar.
     *
     * @param string $name
     * @param Closure $definition
     * @return void
     * @throws Exceptions\DefinitionAlreadyExistsException
     */
    public function register(string $name, Closure $definition)
    {
        $this->registrar->set($name, $definition);
    }

    /**
     * Generate the collection of breadcrumbs from the given route.
     *
     * @param array|null $parameters
     * @return Collection
     * @throws Exceptions\DefinitionNotFoundException
     */
    public function generate(array $parameters = null): Collection
    {
        $route = $this->router->current();

        $parameters = $parameters ? Arr::wrap($parameters) : $route->parameters;

        if ($route && $this->registrar->has($route->getName())) {
            $this->call($route->getName(), $parameters);
        }

        return $this->breadcrumbs;
    }

    /**
     * Call a parent route with the given parameters.
     *
     * @param string $name
     * @param mixed $parameters
     * @return void
     * @throws Exceptions\DefinitionNotFoundException
     */
    public function parent(string $name, ...$parameters)
    {
        $this->call($name, $parameters);
    }

    /**
     * Add a breadcrumb to the collection.
     *
     * @param string $title
     * @param string $url
     * @return void
     */
    public function add(string $title, string $url)
    {
        $this->breadcrumbs->push(new Crumb($title, $url));
    }

    /**
     * Call the breadcrumb definition with the given parameters.
     *
     * @param string $name
     * @param array $parameters
     * @return void
     * @throws Exceptions\DefinitionNotFoundException
     */
    protected function call(string $name, array $parameters)
    {
        $definition = $this->registrar->get($name);

        $parameters = Arr::prepend(array_values($parameters), $this);

        call_user_func_array($definition, $parameters);
    }
}
