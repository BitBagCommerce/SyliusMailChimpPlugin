@newsletter
Feature: Unsubscribing newsletter
  In order to not to receive newsletter emails anymore
  As a customer
  I want to be able to unsubscribe the newsletter

  Background:
    Given the store operates on a single channel in "United States"
    And there is a user "pablo@escobar.co" identified by "PlataOPlomo"
    And I am logged in as "pablo@escobar.co"
    And the "pablo@escobar.co" customer is subscribed to the newsletter
    And this email is also subscribed to the default MailChimp list

  @ui
  Scenario: Unsubscribing during profile update
    Given I am logged in as "pablo@escobar.co"
    When I want to modify my profile
    And I unsubscribe the newsletter
    And I save my changes
    Then I should be notified that it has been successfully edited
    And the email "pablo@escobar.co" should be removed from MailChimp's default list
