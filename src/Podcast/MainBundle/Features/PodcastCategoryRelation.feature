#----------------------------------
# Podcast Category Relationship .feature file
#----------------------------------
    
@RunWith 
Feature: Podcast Category Relationship
    In order to setup a valid catalog
    As a developer
    I need a working relationship

Background:
  Given There is no "Podcast" in database
    And There is no "Category" in database

Scenario: A category contains a podcast
  Given I have a category "Comedy"
    And I have a podcast "Weekend Wogan"
   When I add podcast "Weekend Wogan" to category "Comedy"
   Then I should find podcast "Weekend Wogan" in category "Comedy"

Scenario: A category contains more than 1 podcast
  Given I have a category "Comedy"
    And I have a podcast "Weekend Wogan"
    And I have a podcast "Scott Mills Daily"
   When I add podcast "Weekend Wogan" to category "Comedy"
    And I add podcast "Scott Mills Daily" to category "Comedy"
   Then I should find podcast "Weekend Wogan" in category "Comedy"
    And I should find podcast "Scott Mills Daily" in category "Comedy"

Scenario: A podcast is part of more than 1 category
    Given I have a podcast "Weekly Wogan"        
    And I have a category "Comedy"                    
    And I have a category "Entertainment"                                  
    When I add podcast "Weekly Wogan" to category "Comedy"
    And I add podcast "Weekly Wogan" to category "Entertainment"
    Then I should find podcast "Weekly Wogan" in category "Comedy" 
    And I should find podcast "Weekly Wogan" in category "Entertainment"        