@subscribing_newsletter
Feature: Updating not subscribed customer
  In order to be up-to-date with products and promotions
  I want to be able to subscribe to the newsletter

  Background:
    Given the store operates on a single channel
    And there is no customer with "pablo@escobar.com" email
    And there is a created list in MailChimp with specified ID

  @javascript
  Scenario: Subscribing to newsletter as guest
    Given I want to subscribe to the newsletter
    When I fill newsletter with "pablo@escobar.co" email
    And I subscribe to it
    Then the "pablo@escobar.co" customer should be created
    And this customer should be subscribed to the newsletter
    And I should be notified that I am subscribed to the newsletter
    And the email "pablo@escobar.co" should be exported to MailChimp's default list