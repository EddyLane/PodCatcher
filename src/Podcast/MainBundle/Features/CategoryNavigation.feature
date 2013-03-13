@mink:symfony2
Feature: Navigating the categories within the category directory
  In order to view the podcasts within the catalog
  As a administrator
  I want to browse the categories

  Background:
    Given There is no "Podcast" in database
      And There is no "Category" in database
      And I have a category "Comedy"
      And I have a category "Entertainment"

  Scenario: The categories are being listed
    Given I am on "/categories"
     Then I should see a "table#category-table" element
      And I should see "Comedy" in the "table#category-table" element
      And I should see "Entertainment" in the "table#category-table" element

  Scenario: The categories link to their podcasts list
    Given I am on "/categories"
      And I have a podcast "Weekend Wogan"
      And I add podcast "Weekend Wogan" to category "Comedy"
      And I have a podcast "Scott Mills Daily"
      And I add podcast "Scott Mills Daily" to category "Entertainment"
     When I follow "Entertainment"
     Then I should see "Scott Mills Daily" in the "table#podcast-table" element
      And I should not see "Weekend Wogan" in the "table#podcast-table" element
     When I move backward one page
      And I follow "Comedy"
     Then I should see "Weekend Wogan" in the "table#podcast-table" element
      And I should not see "Scott Mills Daily" in the "table#podcast-table" element

  Scenario: The categories edit anchor links to their edit form
    Given I am on "/categories"
      And I have a podcast "Weekend Wogan"
      And I add podcast "Weekend Wogan" to category "Comedy"
      And I have a podcast "Scott Mills Daily"
      And I add podcast "Scott Mills Daily" to category "Entertainment"
     When I follow "edit-entertainment"
     Then I should see "Edit Entertainment" in the "div#content-header h1" element
      And I should not see "Edit Comedy" in the "div#content-header h1" element
     When I move backward one page
      And I follow "edit-comedy"
     Then I should see "Edit Comedy" in the "div#content-header h1" element
      And I should not see "Edit Entertainment" in the "div#content-header h1" element

  Scenario: The categories delete form deletes the category
    Given I am on "/categories"
      When I follow "edit-entertainment"
       And I press "Delete"
      Then I should not see "Entertainment" in the "table#category-table" element
       And I should see "Comedy" in the "table#category-table" element