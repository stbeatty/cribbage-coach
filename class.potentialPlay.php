<?php 

/**
 * A potential play is a separation of 6 dealt cards in 2-person cribbage
 * into 4 kept cards and 2 discarded cards.
 */
class PotentialPlay {

    private $discards = array();
    private $holds = array();
    private $hand = array();
    private $averageHand;

    public function discard($card) {
        $this->discards[] = $card;
        $this->hand[] = $card;
    }

    public function keep($cards) {
        $this->holds = $cards;
        $this->hand = array_merge($this->hand, $cards);
    }

    public function getDiscards() {
        return join(", ", $this->discards);
    }

    public function getHolds() {
        return join(", ", $this->holds);
    }

    public function getAverageHand() {
        if (!$this->averageHand) {
            $this->averageHand = $this->calculateAverageHand();
        }
        return $this->averageHand;
    }


    private function calculateAverageHand() {
        $total = 0;
        foreach (Card::$DECK as $starter => $value) {
            $total += $this->getHandValue($starter);
        }
        // var_dump($total);
        return round($total / 46, 2);
    }

    private function getHandValue($starter) {
        $score = 0;
        $hand = $this->holds;
        $hand[] = $starter;
        $frequency = $this->getCardFrequency($starter);
        $score += $this->countFifteens($hand);
        $score += $this->countPairs($hand);
        $score += $this->countRuns($hand);
        // var_dump(array(
        //     'hand' => join(", ", $hand),
        //     'fifteens' => $this->countFifteens($hand),
        //     'pairs' => $this->countPairs($hand),
        //     'runs' => $this->countRuns($hand),
        //     'frequency' => $frequency,
        //     'total' => $score
        // ));
        return $score * $frequency;
    }

    private static function sortPositions($a, $b) {
        return $a['position'] > $b['position'];
    }

    private function getHandPositions($hand) {
        $positions = array();
        $keys = array_keys(Card::$DECK);
        foreach ($hand as $card) {
            $positions[] = array('position' => array_search($card, $keys), 'used' => false);
        }
        usort($positions, array($this, 'sortPositions'));
        return $positions;
    }

    private function splitDuplicates($hand, $positions) {
        // var_dump($hand);
        $counts = array_count_values($hand);
        $num_copies = 1;
        foreach ($counts as $card => $count) {
            // var_dump("count $count");
            $num_copies *= $count;
            // var_dump("num copies $num_copies");
        }
        return array_fill(0, $num_copies, $positions);
    }

    public function countRuns($hand) {
        $points = 0;
        $positions = $this->getHandPositions($hand);
        $allruns = $this->splitDuplicates($hand, $positions);
        // var_dump($allruns);
        foreach ($allruns as $run) {
            $points += $this->collectRuns($run);
        }
        // var_dump("total points $points");
        return $points;
    }

    private function collectRuns($run) {
        $streak = 1;
        $pos = $run;
        for ($i = sizeof($pos) - 1; $i > 0; --$i) {
            $thisval = $pos[$i]['position'];
            $nextval;
            if (isset($pos[$i-1])) {
                $nextval = $pos[$i-1]['position'];
            }
            // var_dump(array('thisval' => $thisval, 'nextval' => $nextval));
            if (isset($nextval) && $thisval == $nextval) {
                continue;
            } else if (isset($nextval) && $thisval - 1 != $nextval) {
                if ($streak > 2) {
                    return $streak;
                }
            } else if (!$pos[$i]['used']) {
                $streak ++;
                $pos[$i]['used'] = true;
                // var_dump("incrementing $streak");
            }
            array_pop($pos);
        }
        if ($streak > 2) {
            return $streak;
        }
    }

    private function countPairs($hand) {
        $points = 0;
        $frequencies = array_count_values($hand);
        foreach ($frequencies as $card => $frequency) {
            if ($frequency > 1) {
                $points += (pow($frequency, 2) - $frequency);
            }
        }
        return $points;
    }

    // This might be able to be improved
    // http://stackoverflow.com/questions/12807855/algorithm-extract-subset-based-on-property-sum?noredirect=1&lq=1
    private function countFifteens($hand) {
        $limit = 15;
        $values = array();
        $points = 0;
        foreach ($hand as $card) {
            $values[] = Card::$DECK[$card];
        }
        $count = count($values);
        $total = pow(2, $count);
        for ($i = 0; $i < $total; $i++) {
            $comb = array();
            for ($j = 0; $j < $count; $j++) {
                if (pow(2, $j) & $i) {
                    $comb[] = $values[$j];
                }      
            }
            if (array_sum($comb) == $limit) {
                $points += 2;
            }
        }
        return $points;
    }

    private function getCardFrequency($card) {
        $counts = array_count_values($this->hand);
        if (array_key_exists($card, $counts)) {
            return 4 - $counts[$card];
        }
        return 4;
    }

}
