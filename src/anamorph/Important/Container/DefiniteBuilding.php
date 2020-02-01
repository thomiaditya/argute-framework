<?php

namespace Anamorph\Important\Container;

use Anamorph\Covenant\Container\Container;

class DefiniteBuilding
{
   /**
    * Container that used.
    *
    * @var Anamorph\Covenant\Container\Container
    */
   protected $container;

   /**
    * Abstract that needs definite build.
    *
    * @var string
    */
   protected $abstract;

   protected $concretes = [];

   /**
    * Give value to property
    *
    * @param Anamorph\Covenant\Container\Container $container
    * @param string $abstract
    */
   public function __construct(Container $container, $abstract)
   {
      $this->container = $container;
      $this->abstract = $abstract;
   }

   /**
    * Condition when to fill the needs.
    *
    * @param string $concrete

    * @return void
    */
   public function when(...$concretes)
   {
      $this->concretes = $concretes;

      return $this;
   }

   /**
    * What should be given to abstract.
    *
    * @param string $needs

    * @return void
    */
   public function give($need)
   {
      foreach($this->concretes as $concrete) {
         $this->container->addDefinite($concrete, $this->abstract, $need);
      }
   }
} 