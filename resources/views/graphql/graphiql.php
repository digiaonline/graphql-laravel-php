<!DOCTYPE html>
<html>
<head>
    <title>GraphiQL</title>
    <link rel="stylesheet" href="//unpkg.com/normalize.css"/>
    <link rel="stylesheet" href="//unpkg.com/graphiql@^0.11.11/graphiql.css"/>
</head>
<body>
<div id="graphiql" style="height: 100vh;"></div>
<script src="//unpkg.com/whatwg-fetch@2.0.4/fetch.js"></script>
<script src="//unpkg.com/react@16.3.0/umd/react.production.min.js"></script>
<script src="//unpkg.com/react-dom@16.3.0/umd/react-dom.production.min.js"></script>
<script src="//unpkg.com/graphiql@^0.11.11/graphiql.min.js"></script>
<script>
    function graphQLFetcher(graphQLParams) {
        return fetch('/graphql', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(graphQLParams)
        })
            .then(function (response) {
                return response.text();
            })
            .then(function (responseBody) {
                try {
                    return JSON.parse(responseBody);
                } catch (error) {
                    return responseBody;
                }
            });
    }

    ReactDOM.render(
        React.createElement(GraphiQL, {fetcher: graphQLFetcher}),
        document.getElementById('graphiql')
    );
</script>
</body>
</html>
