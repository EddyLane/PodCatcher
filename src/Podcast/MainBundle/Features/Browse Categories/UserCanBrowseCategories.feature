@browse-categories
Feature: A user can browse categories of podcasts

    Background:
      Given There is no "Podcast" in database
      And There is no "Category" in database
      And there are categories:
            | name                                  |
            | Sport                                 |
            | Comedy                                |
            | Drama                                 |

       And there are podcasts:
            | name                                  | link                                                             | image |
            | Manchester Sports: In The Spotlight   | http://downloads.bbc.co.uk/podcasts/manchester/spotlight/rss.xml |

       And I add podcast "Manchester Sports: In The Spotlight" to category "Sport"

   Scenario: On every view I can see a category list
      When I go to the homepage
      Then I should see "Sport"
       And I should see "Drama"
       And I should see "Comedy"
       And I should not see "Politics"
   
   Scenario: I can navigate the categories 
      When I go to the homepage
       And I follow "Sport"
      Then I should get a json response containing podcast "Manchester Sports: In The Spotlight"
       And I go to the homepage
       And I follow "Comedy"
      Then I should get a json response not containing podcast "Manchester Sports: In The Spotlight"

        