Feature: DateTimeController caching

    Scenario: Caching the response in the user-agent's private cache
        When I am on "/"
        Then the response status code should be 200
        And the expiration time should be 5 seconds
        And the response should be public

    Scenario: The origin server keeps the response in a shared cache
        Given the HttpCache is enabled
        When I am on "/"
        And reload the page
        Then the response is served from cache
        And the HttpCache trace contains 'GET /: fresh'
