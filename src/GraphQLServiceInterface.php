<?php

namespace Digia\GraphQL\Laravel;

/**
 * Interface GraphQLServiceInterface
 * @package Digia\GraphQL\Laravel
 */
interface GraphQLServiceInterface
{

    /**
     * @param string      $query
     * @param array       $variables
     * @param null|string $operationName
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function executeQuery(string $query, array $variables, ?string $operationName): array;
}
