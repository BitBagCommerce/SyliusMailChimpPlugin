@newsletter
Feature: Changing customer newsletter email
  As an admin I want to be able to change customer email address
  and keep him subscribed to mailing list
  while his old email address is removed

  Background:
    Given the store operates on a single channel
    And the store has customer "Homer Simpson" with email "h.simpson@example.com"
    And there is an existing "h.simpson@example.com" email in MailChimp's default list
    And the "h.simpson@example.com" customer is subscribed to the newsletter
    And there is a created list in MailChimp with specified ID

  Scenario: Subscribing to newsletter as guest
    Given the email "h.simpson@example.com" should be exported to MailChimp's default list
    And this customer should be subscribed to the newsletter
    And I want to edit this customer
    And I specify their email as "homer@simpson.com"
    And I save my changes
    Then the email "homer@simpson.com" should be exported to MailChimp's default list
    And the email "h.simpson@example.com" should not be in MailChimp's list
