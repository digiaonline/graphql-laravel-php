<?php

namespace Digia\GraphQL\Laravel;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GraphQLController
{

    /**
     * @var GraphQLServiceInterface
     */
    private $graphqlService;

    /**
     * GraphQLController constructor.
     *
     * @param GraphQLServiceInterface $graphqlService
     */
    public function __construct(GraphQLServiceInterface $graphqlService)
    {
        $this->graphqlService = $graphqlService;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function handle(Request $request): JsonResponse
    {
        $query         = $request->get('query');
        $variables     = $request->get('variables') ?? [];
        $operationName = $request->get('operationName');

        $result = $this->graphqlService->executeQuery($query, $variables, $operationName);

        return new JsonResponse($result);
    }

    /**
     * Renders the GraphiQL interactive query interface.
     *
     * @return Response
     */
    public function renderGraphiQL(): Response
    {
        return self::createViewResponse('graphql/graphiql.php');
    }

    /**
     * Renders the Playground interactive query interface.
     *
     * @return Response
     */
    public function renderPlayground(): Response
    {
        return self::createViewResponse('graphql/playground.php');
    }

    /**
     * @param string $viewPath
     *
     * @return Response
     */
    protected static function createViewResponse(string $viewPath): Response
    {
        $viewData = \file_get_contents(__DIR__ . '/../resources/views/' . $viewPath);

        if ($viewData === false) {
            throw new \RuntimeException('Failed to read view file from the specified path');
        }

        return new Response($viewData);
    }
}
