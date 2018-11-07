<?php

namespace Digia\GraphQL\Laravel;

use Digia\GraphQL\Error\ErrorHandlerInterface;
use Digia\GraphQL\Error\InvariantException;
use Digia\GraphQL\Execution\ExecutionResult;
use Digia\GraphQL\Language\SyntaxErrorException;
use Digia\GraphQL\Schema\Schema;
use function Digia\GraphQL\execute;
use function Digia\GraphQL\parse;
use function Digia\GraphQL\validate;
use function Digia\GraphQL\validateSchema;

/**
 * Class GraphQLService
 * @package Digia\GraphQL\Laravel
 */
class GraphQLService implements GraphQLServiceInterface
{

    /**
     * @var Schema
     */
    protected $schema;

    /**
     * @var ErrorHandlerInterface
     */
    protected $errorHandler;

    /**
     * GraphQLService constructor.
     *
     * @param Schema                $schema
     * @param ErrorHandlerInterface $errorHandler
     */
    public function __construct(Schema $schema, ErrorHandlerInterface $errorHandler)
    {
        $this->schema       = $schema;
        $this->errorHandler = $errorHandler;
    }

    /**
     * @inheritdoc
     */
    public function executeQuery(string $query, array $variables, ?string $operationName): array
    {
        // Validate the schema
        $schemaValidationErrors = validateSchema($this->schema);

        if (!empty($schemaValidationErrors)) {
            return (new ExecutionResult([], $schemaValidationErrors))->toArray();
        }

        // Parse the query
        try {
            $document = parse($query);
        } catch (SyntaxErrorException | InvariantException $error) {
            return (new ExecutionResult([], [$error]))->toArray();
        }

        // Validate the query against the schema
        $validationErrors = validate($this->schema, $document);

        if (!empty($validationErrors)) {
            return (new ExecutionResult([], $validationErrors))->toArray();
        }

        // Execute the query and return the results
        $result = execute($this->schema, $document, null, null, $variables, $operationName, null, $this->errorHandler);

        return $result->toArray();
    }
}
