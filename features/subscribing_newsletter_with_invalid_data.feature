@newsletter
Feature: Validating newsletter form
  In order to be sure that subscribed emails are valid
  As a guest
  I want to be able to be notified about any errors during subscription process

  Background:
    Given the store operates on a single channel
    And there is a created list in MailChimp with specified ID

  Scenario: Subscribing to newsletter with invalid email
    When I want to subscribe to the newsletter
    And I fill newsletter with "lubieplacki" email
    And I subscribe to it
    Then I should be notified about invalid email address

  Scenario: Subscribing to newsletter with invalid CSRF token
    When I want to subscribe to the newsletter
    And the form token is set to "haczek_cwaniaczek"
    And I subscribe to it
    Then I should be notified that the submitted CSRF token is invalid

  Scenario: Subscribing to the newsletter with existing email
    Given there is an existing customer with "los@pepes.co" email
    And this customer is also subscribed to the newsletter
    When I want to subscribe to the newsletter
    And I fill newsletter with "los@pepes.co" email
    And I subscribe to it
    Then I should be notified that the submitted email is already subscribed to the newsletter