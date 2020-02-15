<?php

namespace Anamorph\Important\Container;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use LogicException;
use Anamorph\Covenant\Container\Container as ContainerInterface;
use Anamorph\Important\Container\NotFoundException;

class Container implements ContainerInterface
{
    /** 
     * Contains all define object while define method was called.
     *
     * @var array[]
     */
    protected $defines = [];

    /**
     * Parameters from Parameter class.
     *
     * @var array[]
     */
    protected $params = [];

    /**
     * Parameters needed to build.
     *
     * @var array[]
     */
    protected $with = [];

    /**
     * Object stucked in this property while build.
     * After builded, it will be empty.
     *
     * @var array[]
     */
    protected $buildStuck = [];
    
    /**
     * All instance for singleton purpose.
     *
     * @var object[]
     */
    protected $instances = [];

    /**
     * All resolved object are save here.
     *
     * @var bool[]
     */
    protected $resolved = [];

    /**
     * Contain all paths.
     *
     * @var array[]
     */
    protected $paths = [];

    /**
     * Holds all namespaces instance.
     *
     * @var array[]
     */
    protected $namespaces = [];

    /**
     * All named abstract.
     *
     * @var array[]
     */
    protected $names = [];

    /**
     * Names with abstract offset.
     *
     * @var array[]
     */
    protected $abstractNames = [];
    
    /**
     * Definite build.
     *
     * @var array[]
     */
    protected $definites = [];

    /**
     * Are the given abstract duplicated?
     * 
     * Duplicated means that its not a singleton.
     *
     * @param string $abstract
     * 
     * @return boolean
     */
    protected function isDuplicated($abstract)
    {
        if (isset($this->defines[$abstract]['duplicated'])) {
            return $this->defines[$abstract]['duplicated'];
        }

        return true; 
    }

    /**
     * Set a namespace instance.
     *
     * @param string $abstract
     * @param string $namespace
     * 
     * @return void
     */
    public function namespace($abstract, $namespace)
    {
        if(! isset($this->namespaces[$abstract])) {
            $this->namespaces[$abstract] = $namespace;
        }
    }

    /**
     * Get namespace from namespaces property.
     *
     * @param string $abstract
     * @return string
     */
    public function getNamespace($abstract)
    {
        try {
            return $this->namespaces[$abstract];
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * Define a path to container.
     *
     * @param string $path
     * @param string $directory
     * 
     * @return void
     */
    public function path($path, $directory)
    {
        $this->paths[$path] = $directory;
    }

    /**
     * Getting the path given.
     *
     * @param string $path
     * 
     * @return string
     * 
     * @throws Exception
     */
    public function getPath($path)
    {
        try {
            return $this->paths[$path];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Develop an object from abstract given.
     *
     * @param string $object
     * 
     * @return object Return object from the abstract.
     */
    public function develop($class)
    {
        return $this->resolve($class);
    }

    /**
     * Add a definite to its property.
     *
     * @param string $abstract
     * @param string $concrete
     * @param string $implement
     * 
     * @return void
     */
    public function addDefinite($abstract, $concrete, $implement)
    {
       $this->definites[$abstract][$concrete] = $implement;
    }

    /**
     * Set an definite build to specify an builded object.
     *
     * @param string $abstract
     * 
     * @return Anamorph\Important\Container\DefiniteBuilding
     */
    public function definite($abstract)
    {
        return new DefiniteBuilding($this, $abstract);
    }

    /**
     * Define an definition to contain in this container.
     * 
     * Its useful for insteface.
     *
     * @param string $abstract
     * @param \Closure|string|null $concrete
     * @param boolean $duplicated
     * 
     * @return Anamorph\Important\Container\Parameter
     */
    public function define($abstract, $concrete = null, $duplicated = true)
    {
        $this->dropExistingDefining($abstract);

        if(is_null($concrete)) {
            $concrete = $abstract;
        }

        // We add an object of Parameter Class to use its method. 
        $parameters = new Parameter($this, $concrete);

        if(! $concrete instanceof Closure) {
            $concrete = $this->makeClosure($concrete);
        }

        $this->defines[$abstract] = compact('concrete', 'duplicated');

        return $parameters;
    }

    /**
     * @inheritDoc
     */
    public function instance($abstract, $instance)
    {
        if(! is_object($instance)) {
            throw new LogicException("The instance must be an object instance!");
        }

        $this->instances[$abstract] = $instance;
    }

    /**
     * Add parameter to params property.
     *
     * @param string $abstract
     * @param array[] $parameters
     * 
     * @return void
     */
    public function addToParams($abstract, $parameters)
    {
        $this->params[$abstract] = $parameters;
    }

    /**
     * Create a closure for string concrete.
     *
     * @param string $concrete
     * @return \Closure
     */
    protected function makeClosure($concrete)
    {
        return function ($app) use ($concrete) {
            return $app->resolve($concrete);
        };
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        try {
           return $this->resolve($id);
        } catch (Exception $e) {
            if($this->has($id)) {
                throw $e;
            }
        }

        throw new NotFoundException("Defining with entry {$id} not found!");
    }

    /**
     * Add a singleton instance.
     * 
     * Singleton is a design pattern which allow us to define an instance once
     * and use it in every get method.
     *
     * @param string $abstract
     * @param \Closure|string|null $concrete
     * 
     * @return Anamorph\Important\Container\Parameter
     */
    public function singleton($abstract, $concrete = null)
    {
        return $this->define($abstract, $concrete, false);
    }

    /**
     * Determine wheter the abstract need a params or not.
     *
     * @param string $abstract
     * 
     * @return bool
     */
    protected function needParams($abstract) : bool
    {
        return isset($this->params[$abstract]);
    }

    /**
     * Determine the given abstract has been resolved.
     *
     * @param string $abstract
     * 
     * @return boolean
     */
    protected function isResolved($abstract)
    {
        return isset($this->resolved[$abstract]);
    }

    /**
     * Resolve an object.
     *  
     * @param string $abstract
     * 
     * @return object
     */
    protected function resolve($abstract)
    {
        // First, we get the name if exist in names property, and return to variable
        // abstract, so now abstract has been changed to the name, or if it doesnt
        // have an name, it will return the abstract itself, nothing change.
        $abstract = $this->getName($abstract);
        
        $instanceExist = $this->isInstanceExist($abstract);
        
        // In this line, we do check to params property whether that abstract
        // needs parameter. And we add that parameter to the with property.
        // So the 'with' property will be available until the end of build.
        if($this->needParams($abstract)) {
            $this->with[$abstract] = $this->params[$abstract];
        }
        
        // Check whether this abstract need to build. If this abstract needs build
        // it doesnt listed on instance property so its need to build. But, if not
        // it will return the instance on instance relevant.
        if(! $this->needBuild($abstract)) {
            return $resolvedObject = $this->instances[$abstract];
        }

        $concrete = $this->getConcrete($abstract);
        
        $resolvedObject = $this->build($concrete);

        // Consider if the abstract can duplicated, duplicated means that instance
        // can duplicated or its called a singleton means that it just called once
        // and if we call again its just return the same instance.
        if (! $this->isDuplicated($abstract) && ! $instanceExist) {
           $this->instances[$abstract] = $resolvedObject;
        }
        
        // Set the resolved object to resolved property to make sure object that we build
        // has been resolved in past.
        $this->resolved[$abstract] = true;
        
        return $resolvedObject;
    }

    /**
     * Get the name from given abstract if exist.
     * 
     * If doesnt exist, it will return abstract itself.
     *
     * @param string $abstract
     * 
     * @return string
     */
    protected function getName($abstract)
    {
        if (! isset($this->abstractNames[$abstract])) {
            return $abstract;
        }

        return $this->abstractNames[$abstract];
    }

    /**
     * Set to the name of an abstract.
     *
     * @param string $name
     * @param string $abstract
     * 
     * @return void
     */
    public function name($abstract, $name)
    {
        if($name == $abstract) {
            throw new LogicException("Cannot name itself!");
        }

        $this->names[$name][] = $abstract;
        $this->abstractNames[$abstract] = $name;
    }

    /**
     * Consider if the abstract needs build or not.
     *
     * @param string $abstract
     * 
     * @return bool
     */
    protected function needBuild($abstract) : bool
    {
        return (! isset($this->instances[$abstract]));
    }

    /**
     * Determine instance property has existed.
     *
     * @param string $abstract
     * 
     * @return boolean
     */
    protected function isInstanceExist($abstract)
    {
        return (! $this->needBuild($abstract));
    }

    /**
     * Build object from resolve function.
     *
     * @param \Closure|string $concrete
     * 
     * @return object
     */
    protected function build($concrete)
    {
        if($concrete instanceof Closure) {
            return $object = $concrete($this);
        }
        
        $this->buildStuck[] = $concrete;

        if(is_string($concrete)) {
            $strConcrete = $concrete;

            $object = $this->resolveClass($strConcrete);
        }

        array_pop($this->buildStuck);

        // And now we are checking whether the buildStuck property is empty.
        // if its empty, we will flush the with property too so, both of that
        // will still empty.
        if(empty($this->buildStuck)) {
            $this->flushWith();
        }

        return $object;
    }

    /**
     * Resolve a class from string to object initiation.
     *
     * @param string $concrete
     * 
     * @return object
     */
    protected function resolveClass(string $concrete)
    {
        $reflector = new ReflectionClass($concrete);
        
        if(! $reflector->isInstantiable()) {
            throw new ClassNotInstantiableException(
                "{$concrete} class cannot be instantiate! Please check the class name or the class type!"
            );
        }

        $constructor = $reflector->getConstructor();
        
        // Determine whether the reflector has a constructor or not. We use the function
        // from getContructor and we check if its return null means that its object do
        // not have an constructor and will return the ReflectionMethod otherwise.
        if(is_null($constructor)) {
            return $object = $reflector->newInstanceWithoutConstructor();
        }
        
        $parameters = $this->resolveParameterFromMethod($constructor);

        $object = $reflector->newInstanceArgs($parameters);
        
        return $object;
    }

    /**
     * Resolve the parameters from class.
     *
     * @param \ReflectionMethod|string $method
     * 
     * @return array Return the parameter array from the method.
     */
    protected function resolveParameterFromMethod($method)
    {
        if($method instanceof ReflectionMethod) {
            $parameters = $method->getParameters();
        }

        /** @todo Get parameters with 'string' type of method. It can be separated with @ or it can be whatever. */

        $resolvedParameters = [];
        
        foreach($parameters as $parameter) {
            if (isset($this->with[end($this->buildStuck)][$parameter->name])) {
                $resolvedParameters[] = $this->resolveType(
                    $parameter->name, end($this->buildStuck)
                );
                continue;
            }
            
            // Get the class from parameter, as PHP function of Reflection Parameter,
            // it will be returned null if its doesnt a class type hinting
            // so we will check if its has a class or not and we do resolve.
            $class = $parameter->getClass();
            $resolvedParameters[] = is_null($class)
                                    ? $this->resolveType($parameter) 
                                    : $this->resolve($class->name);
        }
        
        return $resolvedParameters;
    }

    /**
     * Resolve the type of each parameter given.
     * 
     * This function will be called if the parameter doesnt an object.
     *
     * @param \ReflectionParameter|array $parameter
     * 
     * @return string
     */
    protected function resolveType($parameter, $concrete = null)
    {
        if($parameter instanceof ReflectionParameter) {
            return $parameter->name;
        }

        /** @todo Repair this function! */
         
        $resolvedType = $this->with[$concrete][$parameter];

        unset($this->with[$concrete][$parameter]);

        return $resolvedType;
    }

    /**
     * Flush the with property.
     *
     * @return void
     */
    protected function flushWith()
    {
        $this->with = [];
    }

    /**
     * Drop defining with the abstract given.
     *
     * @param string $abstract
     * 
     * @return void
     */
    protected function dropExistingDefining($abstract)
    {
        unset($this->defines[$abstract]);
        unset($this->instances[$abstract]);
    }

    /**
     * Get the concrete from abstract parameter.
     *
     * @param string $abstract
     * 
     * @return \Closure|string
     */
    protected function getConcrete($abstract)
    {
        if (! is_null($definite = $this->getDefinite($abstract))) {
            return $definite;
        }
        
        if ($this->has($abstract)) {
            return $this->defines[$abstract]['concrete'];
        }
        
        return $abstract;
    }

    protected function searchDefinite($abstract)
    {
        if(isset($this->definites[end($this->buildStuck)][$abstract])) {
            return $this->definites[end($this->buildStuck)][$abstract];
        }
    }

    /**
     * Get the definite from abstract.
     *
     * @param string $abstract
     * 
     * @return string|null
     */
    protected function getDefinite($abstract)
    {
        if(! is_null($founded = $this->searchDefinite($abstract))) {
            return $founded;
        }
        
        if(empty($this->names[$abstract])) {
            return;
        }

        foreach($this->names[$abstract] as $name) {
            if (! is_null($founded = $this->searchDefinite($name))) {
                return $founded;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function has($id)
    {
        return isset($this->defines[$id]);
    }

    /**
     * ArrayAccess function. Set the define object with array.
     *
     * @param string $offset
     * @param \Closure|string|null $value
     * 
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            throw new LogicException("No offset defining!");
        }

        $this->define($offset, $value);
    }

    /**
     * ArrayAccess function. Determine if the offset exist.
     *
     * @param string $offset
     * 
     * @return void
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * ArrayAccess function. Unset the given offset.
     *
     * @param string $offset
     * 
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->dropExistingDefining($offset);
    }

    /**
     * ArrayAccess function. Get the following offset.
     *
     * @param string $offset
     * 
     * @return void
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }
}