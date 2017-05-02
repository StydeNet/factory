<?php

namespace Styde\Factory;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\FactoryBuilder as BaseFactoryBuilder;

class FactoryBuilder extends BaseFactoryBuilder
{
    /**
     * The factory object linked to this builder.
     *
     * @var \Styde\Factory\Factory
     */
    protected $factory;

    /**
     * Create an new builder instance.
     *
     * @param  \Styde\Factory\Factory  $factoryClass
     * @return void
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
        $this->class = $factory->model();
    }

    /**
     * Get a raw attributes array for the model.
     *
     * @param  array  $attributes
     * @return mixed
     */
    protected function getRawAttributes(array $attributes = [])
    {
        return $this->callClosureAttributes(
            array_merge(
                $this->applyStates($this->factory->data($attributes), $attributes),
                $attributes
            )
        );
    }

    /**
     * Make an instance of the model with the given attributes.
     *
     * @param  array  $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function makeInstance(array $attributes = [])
    {
        return Model::unguarded(function () use ($attributes) {
            return new $this->class(
                $this->getRawAttributes($attributes)
            );
        });
    }

    /**
     * Apply the active states to the model definition array.
     *
     * @param  array  $definition
     * @param  array  $attributes
     * @return array
     */
    protected function applyStates(array $definition, array $attributes = [])
    {
        foreach ($this->activeStates as $state) {
            $definition = array_merge(
                $definition, $this->factory->getAttributesFromState($state, $attributes)
            );
        }

        return $definition;
    }
}
