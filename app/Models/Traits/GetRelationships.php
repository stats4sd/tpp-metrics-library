<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use ReflectionMethod;


/**
 * Copied and adapted from https://gist.github.com/shanginn/aadddec758efb595be2e8be6a69b99c4
 */
trait GetRelationships
{

    protected static array $availableRelations = [];
    protected static array $availableManyToManyRelations = [];
    public static function getAvailableRelations(): array
    {


        if (isset(static::$availableRelations[static::class])) {
            return static::$availableRelations[static::class];
        }

        $methods = (new \ReflectionClass(static::class))->getMethods(\ReflectionMethod::IS_PUBLIC);

        $relationMethods = array_reduce(
            $methods,
            function ($result, ReflectionMethod $method) {
                // If this function has a return type
                ($returnType = (string)$method->getReturnType()) &&

                // And the function returns a relation
                is_subclass_of($returnType, Relation::class) &&

                ($result = array_merge($result, [$method->getName() => $returnType]));

                return $result;
            },
            []);

        return static::setAvailableRelations($relationMethods);
    }

    public static function getAvailableManyToManyRelations(): array
    {
        $relations = static::$availableRelations[static::class] ?? static::getAvailableRelations();

        return array_filter($relations, function($relation) {
            return Str::endsWith($relation, ['BelongsToMany', 'MorphToMany']);
        });
    }


    public static function setAvailableRelations(array $relations): array
    {
        static::$availableRelations[static::class] = $relations;

        return $relations;
    }

    public static function setAvailableManyToManyRelations(array $relations): array
    {
        static::$availableManyToManyRelations[static::class] = $relations;

        return $relations;
    }
}
