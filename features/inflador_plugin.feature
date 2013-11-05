Feature: InfladorPlugin
    In order to generate a site with Inflador
    As a cool person
    I need to be able to use the InfladorPlugin

    Scenario: Files indicated explicitely are copied
        Given I have the inflador config file:
            """
            plugins:
                - Inflador\Plugin\InfladorPlugin\InfladorPlugin

            inflador:
                url:  http://inflador.org
                path: /
                static:
                    explicits: [CNAME, file.txt]
            """
        And I have the source file "CNAME" that contains "inflador.org"
        And I have the source file "file.txt" that contains "foo"
        And I have the source file "style.css" that contains "bar"
        When I run the inflador command "process"
        Then The command should be successful
        And I should see the destination file "CNAME" that contains "inflador.org"
        And I should see the destination file "file.txt" that contains "foo"
        And I should not see the destination file "style.css"

    Scenario: Files with indicated extensions are copied
        Given I have the inflador config file:
            """
            plugins:
                - Inflador\Plugin\InfladorPlugin\InfladorPlugin

            inflador:
                url:  http://inflador.org
                path: /
                static:
                    extensions: [css, jpg]
            """
        And I have the source file "style.css" that contains "foo"
        And I have the source file "me.jpg" that contains "bar"
        And I have the source file "file.txt" that contains "ups"
        When I run the inflador command "process"
        Then The command should be successful
        And I should see the destination file "style.css" that contains "foo"
        And I should see the destination file "me.jpg" that contains "bar"
        And I should not see the destination file "file.txt"