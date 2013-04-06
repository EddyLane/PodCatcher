@categories 
Feature: A user can browse categories of podcasts

    Background:
     Given there are categories:
            | name                                  |
            | Sport                                 |
       And there are podcasts:
            | name                                  | link                                                             | category |
            | Manchester Sports: In The Spotlight   | http://downloads.bbc.co.uk/podcasts/manchester/spotlight/rss.xml | Sport    |

   Scenario: On every view I can see a category list
      When I go to the homepage
      Then I should see "Sport"
   