<?php

require_once('Card.php');
require_once('PotentialPlay.php');


/**
 * A Deal is the full amount of cards received from the dealer.
 */
class Deal {
    
    public static $total_num_cards = 6;
    public $hand = array(); // Array of Card objects
    public $possible_plays = array(); // Array of PossiblePlay objects
    private $dealer;

    public function __construct($params) {
        if (!is_array($params)) {
            throw new InvalidArgumentException("Cards supplied are not an array.");
        }
        for ($i=0; $i < Deal::$total_num_cards; $i++) {
            $key = "card$i";
            if (!array_key_exists($key, $params)) {
                throw new Exception("Not enough cards dealt. Currently at [$key].");
            }
            $card = $params["card$i"];
            $this->hand[] = new Card($card);
        }
        $this->dealer = $params['dealer'];
    }

    public function determinePossiblePlays() {
        $full_pool = $this->hand;
        $temphand = $this->hand;
        for ($i = Deal::$total_num_cards - 1; $i >= 0; $i--) {
            $discard_one = array_pop($temphand);
            for ($j = 0; $j < sizeof($temphand); $j++) {
                $remaining_hand = $full_pool;
                $discard_two = $temphand[$j];
                unset($remaining_hand[$i]);
                unset($remaining_hand[$j]);
                $play = new PotentialPlay();
                $play->discard(array($discard_one, $discard_two));
                $play->keep($remaining_hand);
                $this->possible_plays[] = $play;
            }
        }
        if ("self" == $this->dealer) {
            usort($this->possible_plays, array($this, 'sortPlaysByExpectedAverageSelf'));
        } else if ("opponent" == $this->dealer) {
            usort($this->possible_plays, array($this, 'sortPlaysByExpectedAverageOpponent'));
        }
    }

    private function sortPlaysByExpectedAverageSelf($a, $b) {
        return $a->getExpectedAverageSelf() < $b->getExpectedAverageSelf();
    }

    private function sortPlaysByExpectedAverageOpponent($a, $b) {
        return $a->getExpectedAverageOpponent() < $b->getExpectedAverageOpponent();
    }

}
