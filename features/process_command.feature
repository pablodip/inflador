Feature: Process Command
    In order to generate a site with Inflador
    As a cool person
    I need to be able to execute the process command

    Scenario: Execute the process command
        Given I have the inflador config file:
            """
            plugins:
                - Inflador\Plugin\InfladorPlugin\InfladorPlugin
            """
        When I run the inflador command "process"
        Then The command should be successful

    Scenario: Execute the process command without config file
        When I run the inflador command "process"
        Then The command should fail
        And The output should match "/The config file ".+" does not exist/"

    Scenario: Execute the process command with the config file empty
        Given I have the inflador config file:
            """
            """
        When I run the inflador command "process"
        Then The command should fail
        And The output should match "/The config file ".+" is not valid/"

    Scenario: Execute the process command with an invalid plugin class
        Given I have the inflador config file:
            """
            plugins:
                - DateTime
            """
        When I run the inflador command "process"
        Then The command should fail