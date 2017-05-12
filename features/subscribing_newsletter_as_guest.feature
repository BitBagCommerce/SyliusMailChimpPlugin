@newsletter
Feature: Creating new newsletter customer
  In order to be up-to-date with products and promotions
  As a guest
  I want to be able to subscribe to the newsletter

  Background:
    Given the store operates on a single channel
    And there is no customer with "tata@escobar.co" email
    And there is a created list in MailChimp with specified ID

  Scenario: Subscribing to newsletter as guest
    Given I want to subscribe to the newsletter
    When I fill newsletter with "tata@escobar.co" email
    And I subscribe to it
    Then the "tata@escobar.co" customer should be created
    And this customer should be subscribed to the newsletter
    And I should be notified that I am subscribed to the newsletter
    And the email "tata@escobar.co" should be exported to MailChimp's default list