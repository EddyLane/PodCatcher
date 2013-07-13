Feature: User authentication

  Background:
    Given the following users exist:
      | username | email              | password |
      | everzet  | ever.zet@gmail.com | ever.zet |
      And I visited homepage

  Scenario: User logs in to the application
      And I am not authenticated
     When I submit the login form with the following details:
      | username | password |
      | everzet  | ever.zet |
     Then I should be authenticated as "everzet"

  Scenario: User logs out of the application
      And I am authenticated as user "everzet"
     When I click the log out link
     Then I should not be authenticated