Feature: DateTimeController caching

    Scenario: Caching the response in the user-agent's private cache
        When I am on "/"
        Then the response status code should be 200
