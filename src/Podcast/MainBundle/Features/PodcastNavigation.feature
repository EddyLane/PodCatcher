Feature: Navigating the podcasts on the user dashboard
  In order to listen to podcasts
  As a visitor
  I want to browse my podcasts

  Background:
    Given There is no "Podcast" in database
      And There is no "Category" in database
      And I have a podcast "Weekend Wogan"

  Scenario: The podcasts are being listed
    Given I am on "/"
     Then I should see a "li div.weekend-wogan" element

  Scenario: The podcasts link to their view pages
    Given I am on "/"
     When I follow "Weekend Wogan"
     Then I should see "Weekend Wogan" in the "div#content-header h1" element