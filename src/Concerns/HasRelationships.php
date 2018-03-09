<?php

namespace Xenus\Concerns;

use Xenus\Relations;
use Xenus\Exceptions;
use Xenus\Collection;
use MongoDB\Database;

trait HasRelationships
{
    /**
     * Instantiate a Xenus Collection
     *
     * @param  string $collection
     *
     * @return Collection
     */
    private function build(string $collection)
    {
        if (null === $this->collection) {
            throw new Exceptions\LogicException(sprintf('Target collection "%s" is not buildable', $collection));
        }

        if (false === class_exists($collection)) {
            throw new Exceptions\InvalidArgumentException(sprintf('Target collection "%s" does not exist', $collection));
        }

        return new Collection(new Database($this->collection->getManager(), $this->collection->getDatabaseName()), [
            'name' => $collection::NAME,
            'document' => $collection::DOCUMENT
        ]);
    }

    /**
     * Define a "hasOne" relationship
     *
     * @param  string  $target
     * @param  string  $targetKey
     * @param  string  $localKey
     *
     * @return Relations\BindOne
     */
    protected function hasOne(string $target, string $targetKey, string $localKey = '_id')
    {
        $object = $this;
        $target = $this->build($target);

        return new Relations\BindOne($target, $object, $targetKey, $localKey);
    }

    /**
     * Define a "hasMany" relationship
     *
     * @param  string  $target
     * @param  string  $targetKey
     * @param  string  $localKey
     *
     * @return Relations\BindMany
     */
    protected function hasMany(string $target, string $targetKey, string $localKey = '_id')
    {
        $object = $this;
        $target = $this->build($target);

        return new Relations\BindMany($target, $object, $targetKey, $localKey);
    }

    /**
     * Define a "belongsTo" relationship
     *
     * @param  string $target
     * @param  string $localKey
     * @param  string $targetKey
     *
     * @return Relations\BindOne
     */
    protected function belongsTo(string $target, string $localKey, string $targetKey = '_id')
    {
        $object = $this;
        $target = $this->build($target);

        return new Relations\BindOne($target, $object, $targetKey, $localKey);
    }

    /**
     * Define a "belongsToMany" relationship
     *
     * @param  string $target
     * @param  string $localKey
     * @param  string $targetKey
     *
     * @return Relations\BindMany
     */
    protected function belongsToMany(string $target, string $localKey, string $targetKey = '_id')
    {
        $object = $this;
        $target = $this->build($target);

        return new Relations\BindMany($target, $object, $targetKey, $localKey);
    }
}