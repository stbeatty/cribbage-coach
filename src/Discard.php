<?php

/**
 * Average crib values for discard combinations according to Michael Schell
 * http://cribbageforum.com/SchellDiscard.htm
 */
class Discard {

    public $averageCribSelf;
    public $averageCribOpponent;

    public function __construct($cards) {
        if (count($cards) != 2) {
            throw new Exception('Crib does not contain 2 cards.');
        }

        foreach (Discard::$VALUES as $values) {
            foreach ($cards as $card) {
                $pos = array_search($card->getFaceValue(), $values);
                if ($pos !== false) {
                    unset($values[$pos]);
                }
            }
            if (count($values) == 2) {
                $this->averageCribSelf = $values[2];
                $this->averageCribOpponent = $values[3];
                return $this;
            }
        }
        throw new Exception("Could not match crib cards [${cards[0]},${cards[1]}] to find average values.");
    }

    // Card 1, Card 2, Value self, Value opponent
    public static $VALUES = array(
        array('A', 'A', 5.38, 6.02),
        array('A', 2, 4.23, 5.07),
        array('A', 3, 4.52, 5.07),
        array('A', 4, 5.43, 5.72),
        array('A', 5, 5.45, 6.01),
        array('A', 6, 3.85, 4.91),
        array('A', 7, 3.85, 4.89),
        array('A', 8, 3.80, 4.85),
        array('A', 9, 3.40, 4.55),
        array('A', 10, 3.42, 4.48),
        array('A', 'J', 3.65, 4.68),
        array('A', 'Q', 3.42, 4.33),
        array('A', 'K', 3.41, 4.3),
        
        array(2, 2, 5.72, 6.38),
        array(2, 3, 7, 7.33),
        array(2, 4, 4.52, 5.33),
        array(2, 5, 5.45, 6.11),
        array(2, 6, 3.93, 4.97),
        array(2, 7, 3.81, 4.97),
        array(2, 8, 3.66, 4.94),
        array(2, 9, 3.71, 4.7),
        array(2, 10, 3.55, 4.59),
        array(2, 'J', 3.84, 4.81),
        array(2, 'Q', 3.58, 4.56),
        array(2, 'K', 3.52, 4.45),

        array(3, 3, 5.94, 6.68),
        array(3, 4, 4.91, 5.96),
        array(3, 5, 5.97, 6.78),
        array(3, 6, 3.81, 4.87),
        array(3, 7, 3.58, 5.01),
        array(3, 8, 3.92, 5.05),
        array(3, 9, 3.78, 4.87),
        array(3, 10, 3.57, 4.63),
        array(3, 'J', 3.90, 4.86),
        array(3, 'Q', 3.59, 4.59),
        array(3, 'K', 3.67, 4.48),

        array(4, 4, 5.63, 6.53),
        array(4, 5, 6.48, 7.26),
        array(4, 6, 3.85, 5.34),
        array(4, 7, 3.72, 4.88),
        array(4, 8, 3.83, 4.94),
        array(4, 9, 3.72, 4.68),
        array(4, 10, 3.59, 4.53),
        array(4, 'J', 3.88, 4.85),
        array(4, 'Q', 3.59, 4.46),
        array(4, 'K', 3.60, 4.36),

        array(5, 5, 8.79, 9.37),
        array(5, 6, 6.63, 7.47),
        array(5, 7, 6.01, 7),
        array(5, 8, 5.48, 6.3),
        array(5, 9, 5.43, 6.15),
        array(5, 10, 6.66, 7.41),
        array(5, 'J', 7, 7.76),
        array(5, 'Q', 6.63, 7.34),
        array(5, 'K', 6.66, 7.25),

        array(6, 6, 5.76, 7.08),
        array(6, 7, 4.98, 6.42),
        array(6, 8, 4.63, 5.86),
        array(6, 9, 5.13, 6.26),
        array(6, 10, 3.17, 4.31),
        array(6, 'J', 3.41, 4.57),
        array(6, 'Q', 3.23, 4.22),
        array(6, 'K', 3.13, 4.14),

        array(7, 7, 5.92, 7.14),
        array(7, 8, 6.53, 7.63),
        array(7, 9, 4.04, 5.26),
        array(7, 10, 3.23, 4.31),
        array(7, 'J', 3.53, 4.68),
        array(7, 'Q', 3.23, 4.32),
        array(7, 'K', 3.26, 4.27),

        array(8, 8, 5.45, 6.82),
        array(8, 9, 4.72, 5.83),
        array(8, 10, 3.8, 5.1),
        array(8, 'J', 3.52, 4.59),
        array(8, 'Q', 3.19, 4.31),
        array(8, 'K', 3.16, 4.2),

        array(9, 9, 5.16, 6.39),
        array(9, 10, 4.29, 5.43),
        array(9, 'J', 3.97, 4.96),
        array(9, 'Q', 2.99, 4.11),
        array(9, 'K', 3.06, 4.03),

        array(10, 10, 4.76, 6.08),
        array(10, 'J', 4.61, 5.63),
        array(10, 'Q', 3.31, 4.61),
        array(10, 'K', 2.84, 3.88),

        array('J', 'J', 5.33, 6.42),
        array('J', 'Q', 4.81, 5.46),
        array('J', 'K', 3.96, 4.77),

        array('Q', 'Q', 4.79, 5.79),
        array('Q', 'K', 3.46, 4.49),
        array('K', 'K', 4.58, 5.65)
    );

}
