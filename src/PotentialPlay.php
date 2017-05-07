<?php

require_once('Card.php');
require_once('Deal.php');
require_once('Discard.php');


/**
 * A potential play is a separation of 6 dealt cards in 2-person cribbage
 * into 4 kept cards and 2 discarded cards.
 */
class PotentialPlay {

    private $discards = array();
    private $holds = array();
    private $hand = array();
    private $averageHand;
    private $discard;
    private $expectedAverageSelf;
    private $expectedAverageOpponent;
    public $hands = array();
    private $suitFrequencies = array();
    private $flushKeepSuit;

    public function __construct() {
        foreach (Card::$SUITS as $suit) {
            $this->suitFrequencies[$suit] = 0;
        }
    }

    public function discard($cards) {
        $this->discards = $cards;
        foreach ($cards as $card) {
            $this->hand[] = $card;
            $this->suitFrequencies[$card->getSuit()] += 1;
        }
        return $this;
    }

    public function keep($cards) {
        $this->holds = $cards;
        $flushInKeep = true;
        $cards = array_values($cards);
        $suit = $cards[0]->getSuit();
        foreach ($cards as $card) {
            $this->hand[] = $card;
            $this->suitFrequencies[$card->getSuit()] += 1;
            if ($card->getSuit() != $suit) {
                $flushInKeep = false;
            }
        }
        if ($flushInKeep) {
            $this->flushKeepSuit = $suit;
        }
        return $this;
    }

    public function getDiscards() {
        return join(", ", $this->discards);
    }

    public function getHolds() {
        return join(", ", $this->holds);
    }

    public function getExpectedAverageSelf() {
        if (!$this->expectedAverageSelf) {
            $this->getExpectedAverage();
        }
        return $this->expectedAverageSelf;
    }

    public function getExpectedAverageOpponent() {
        if (!$this->expectedAverageOpponent) {
            $this->getExpectedAverage();
        }
        return $this->expectedAverageOpponent;
    }

    public function getExpectedAverage() {
        if (!$this->expectedAverageSelf || !$this->expectedAverageOpponent) {
            $this->setAverageHand();
            $this->lookupAverageCribs();
            $this->expectedAverageSelf = $this->averageHand + $this->discard->averageCribSelf;
            $this->expectedAverageOpponent = $this->averageHand + $this->discard->averageCribOpponent;
        }
    }

    private function setAverageHand() {
        if (!$this->averageHand) {
            $this->averageHand = $this->calculateAverageHand();
        }
    }

    private function lookupAverageCribs() {
        if (!$this->discard) {
            $this->discard = new Discard($this->discards);
        }
    }

    public function calculateAverageHand() {
        $total = 0;
        foreach (Card::$VALUES as $starter => $value) {
            $total += $this->getHandValue(new Card($starter.'N'));
        }
        $total += $this->countHisNobsProbability();
        $total += $this->countFlushProbability();
        return round($total / 46, 2);
    }

    private function countFlushProbability() {
        $suit = $this->flushKeepSuit;
        if (!$suit) {
            return 0;
        }
        $frequency = $this->getSuitFrequency($suit);
        $this->hands[] = array(
            'starter' => "flush ($suit)",
            'fifteens' => null,
            'frequency' => $frequency,
            'pairs' => null,
            'runs' => null,
            'score' => 1
        );
        return $frequency;
    }

    private function countHisNobsProbability() {
        $points = 0;
        $suits = [];
        foreach($this->holds as $card) {
            if ($card->getFaceValue() == 'J') {
                $suit = $card->getSuit();
                $frequency = $this->getSuitFrequency($suit);
                $points += $frequency;
                $this->hands[] = array(
                    'starter' => "J ($suit)",
                    'fifteens' => null,
                    'frequency' => $frequency,
                    'pairs' => null,
                    'runs' => null,
                    'score' => 1
                );
            }
        }
        return $points;
    }

    private function getSuitFrequency($suit) {
        return count(Card::$VALUES) - $this->suitFrequencies[$suit];
    }

    private function getHandValue($starter) {
        $score = 0;
        $hand = $this->holds;
        $hand[] = $starter;
        $frequency = $this->getCardFrequency($starter);
        $fifteens = $this->countFifteens($hand);
        $pairs = $this->countPairs($hand);
        $runs = $this->countRuns($hand);
        $score += $fifteens;
        $score += $pairs;
        $score += $runs;
        if ($this->flushKeepSuit) {
            $score += count($this->holds);
        }

        $this->hands[] = array(
            'starter' => $starter,
            'fifteens' => $fifteens,
            'frequency' => $frequency,
            'pairs' => $pairs,
            'runs' => $runs,
            'score' => $score * $frequency
        );
        return $score * $frequency;
    }

    public function countRuns($hand) {
        $points = 0;
        usort($hand, 'Card::sort');
        $runs = $this->collectRuns($hand, array());
        // var_dump($runs);
        $points = array_sum(array_map("count", $runs));
        return $points;
    }

    private function collectRuns($hand, $runs) {
        $hand = array_values($hand);
        $fullhand = $hand;
        // var_dump(['START', 'hand' => $hand]);
        for ($i=count($hand)-1; $i>1; --$i) {
            $candidate = array($hand[$i]);
            for ($j=$i; $j>=0; --$j) {
                $thiscard = $hand[$j];
                $nextcard;
                if (isset($hand[$j-1])) {
                    $nextcard = $hand[$j-1];
                } else {
                    // var_dump(['end' => end($candidate), 'thiscard' => $thiscard]);
                    if (end($candidate)->getIndex() - 1 == $thiscard->getIndex()) {
                        $candidate[] = $thiscard;
                    }
                    $runs = $this->addToRuns($runs, $candidate);
                }
                if (isset($nextcard) && $thiscard->getIndex() == $nextcard->getIndex()) {
                    $handy = $fullhand;
                    unset($handy[array_search($thiscard, $handy)]);
                    $runs = $this->collectRuns($handy, $runs);
                    continue;
                } else if (isset($nextcard) && $thiscard->getIndex() - 1 == $nextcard->getIndex()) {
                    $candidate[] = $nextcard;
                } else {
                    $runs = $this->addToRuns($runs, $candidate);
                    $candidate = array($nextcard);
                }
                // var_dump(['this' => $thiscard, 'next' => $nextcard, 'current' => $candidate, 'runs' => $runs]);
            }
            array_pop($hand);
        }
        return $runs;
    }

    private function addToRuns($runs, $candidate) {
        if (count($candidate) < 3) {
            return $runs;
        }
        // var_dump(['candidate' => $candidate, 'runs' => $runs]);
        foreach ($runs as $run) {
            if (count(array_diff($candidate, $run)) == 0) {
                return $runs;
            }
        }
        $runs[] = $candidate;
        return $runs;
    }

    public function countPairs($hand) {
        $points = 0;
        $frequencies = array();
        foreach ($hand as $card) {
            $value = $card->getFaceValue();
            if (array_key_exists($value, $frequencies)) {
                $frequencies[$value] += 1;
            } else {
                $frequencies[$value] = 1;
            }
        }
        foreach ($frequencies as $card => $frequency) {
            if ($frequency > 1) {
                $points += (pow($frequency, 2) - $frequency);
            }
        }
        return $points;
    }

    // This might be able to be improved
    // http://stackoverflow.com/questions/12807855/algorithm-extract-subset-based-on-property-sum?noredirect=1&lq=1
    public function countFifteens($hand) {
        $limit = 15;
        $values = array();
        $points = 0;
        foreach ($hand as $card) {
            $values[] = $card->getValue();
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

    public function getCardFrequency($starter) {
        $counts = array();
        foreach ($this->hand as $card) {
            $faceValue = $card->getFaceValue();
            if (array_key_exists($faceValue, $counts)) {
                $counts[$faceValue] += 1;
            } else {
                $counts[$faceValue] = 1;
            }
        }
        if (array_key_exists($starter->getFaceValue(), $counts)) {
            return 4 - $counts[$starter->getFaceValue()];
        }
        return 4;
    }

}
