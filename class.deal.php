<?php

class Deal {
    
    private $total_num_cards = 6;
    public $hand = array();
    public $possible_plays = array();

    public function __construct($cards) {
        $this->accept($cards);
    }

    public function determinePossiblePlays() {
        $full_pool = $this->hand;
        $temphand = $this->hand;
        for ($i = $this->total_num_cards - 1; $i >= 0; $i--) {
            $discard_one = array_pop($temphand);
            for ($j = 0; $j < sizeof($temphand); $j++) {
                $remaining_hand = $full_pool;
                $discard_two = $temphand[$j];
                unset($remaining_hand[$i]);
                unset($remaining_hand[$j]);
                $play = new PotentialPlay();
                $play->discard($discard_one);
                $play->discard($discard_two);
                $play->keep($remaining_hand);
                $this->possible_plays[] = $play;
                //var_dump($play->getHandValue());
            }
        }
        usort($this->possible_plays, array($this, 'sortPlaysByAverageHand'));
    }

    private function accept($dealt) {
        for ($i = 1; $i <= $this->total_num_cards; $i++) {
            $card = $dealt["card$i"];
            if (!in_array($card, array_keys(Card::$DECK))) {
                throw new Exception("Card #$i value [$card] not valid.");
            }
            $this->hand[] = $card;
        }
    }

    private function sortPlaysByAverageHand($a, $b) {
        return $a->getAverageHand() < $b->getAverageHand();
    }

}
