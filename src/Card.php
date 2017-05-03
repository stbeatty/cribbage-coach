<?php

/**
 * A class representing the cribbage deck
 */
class Card {

    public static $VALUES = array(
        'A' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        '10' => 10,
        'J' => 10,
        'Q' => 10,
        'K' => 10
    );

    public static $SUITS = array('C', 'D', 'H', 'S');

    private $faceValue;
    private $suit;
    private $value;

    public function __construct($stringValue) {
        if (!is_string($stringValue)) {
            throw new InvalidArgumentException("Card value is not a string.");
        }

        if (strlen($stringValue) < 2 || strlen($stringValue) > 3) {
            throw new Exception("Bad card code [$stringValue] given");
        }

        $faceValue = substr($stringValue, 0, strlen($stringValue) - 1);
        $suit = substr($stringValue, -1, 1);

        if (!in_array($faceValue, array_keys(Card::$VALUES))) {
            throw new Exception("Card face value [$faceValue] not recognized.");
        }

        if (!in_array($suit, Card::$SUITS)) {
            throw new Exception("Card suit value [$suit] not recognized.");
        }

        $this->suit = $suit;
        $this->faceValue = $faceValue;
        $this->value = Card::$VALUES[$faceValue];
    }

    public function __toString() {
        return $this->faceValue . $this->suit;
    }

    public function getFaceValue() {
        return $this->faceValue;
    }

    public function getSuit() {
        return $this->suit;
    }

    public function getValue() {
        return $this->value;
    }
}
