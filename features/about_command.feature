Feature: User sees the about
    In order to see the about
    As an user
    I need to be able torun the about command

    Scenario: Show the about
        When I run the inflador command "about"
        Then The command should be successful