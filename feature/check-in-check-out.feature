Feature: Users can check in and out of buildings

  Scenario: Check-in
    Given a building has been registered
    When "bob" checks into the building
    Then "bob" should have been checked into the building

  Scenario: Double check-in is detected as an anomaly
    Given a building has been registered
    And "bob" checked into the building
    When "bob" checks into the building
    Then "bob" should have been checked into the building
    And a check-in anomaly caused by "bob" should have been detected

