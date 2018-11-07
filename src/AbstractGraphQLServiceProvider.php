<?php

namespace Digia\GraphQL\Laravel;

use Digia\GraphQL\Error\ErrorHandler;
use Digia\GraphQL\Error\ErrorHandlerInterface;
use Digia\GraphQL\Language\Source;
use Digia\GraphQL\Schema\Resolver\ResolverRegistryInterface;
use Digia\GraphQL\Schema\Schema;
use Illuminate\Support\ServiceProvider;
use Jalle19\Laravel\LostInterfaces\Providers\ServiceProvider as ServiceProviderInterface;

/**
 * Class AbstractGraphQLServiceProvider
 * @package Digia\GraphQL\Laravel
 */
abstract class AbstractGraphQLServiceProvider extends ServiceProvider implements ServiceProviderInterface
{

    /**
     * @return Source
     */
    abstract protected function createSchemaDefinition(): Source;

    /**
     * @return ResolverRegistryInterface
     */
    abstract protected function createResolverRegistry(): ResolverRegistryInterface;

    /**
     * @param Source                    $schemaDefinition
     * @param ResolverRegistryInterface $resolverRegistry
     *
     * @return Schema
     */
    abstract protected function createExecutableSchema(
        Source $schemaDefinition,
        ResolverRegistryInterface $resolverRegistry
    ): Schema;

    /**
     * @inheritdoc
     */
    public function register(): void
    {
        $this->app->singleton(GraphQLServiceInterface::class, function () {
            $schemaDefinition = $this->createSchemaDefinition();
            $resolverRegistry = $this->createResolverRegistry();
            $executableSchema = $this->createExecutableSchema($schemaDefinition, $resolverRegistry);
            $errorHandler     = $this->createErrorHandler();

            return new GraphQLService($executableSchema, $errorHandler);
        });
    }

    /**
     * @inheritdoc
     */
    public function boot(): void
    {

    }

    /**
     * @return ErrorHandlerInterface
     */
    protected function createErrorHandler(): ErrorHandlerInterface
    {
        // Dummy implementation that does no special handling
        return new ErrorHandler(function () {

        });
    }
}
