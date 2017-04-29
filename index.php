<?php

define('TOTAL_NUM_CARDS', 6);

class Card {

    public static $DECK = array(
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

}

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

// Tests
// $play = new PotentialPlay();
// $play->discard(2);
// $play->discard('J');
// $play->keep(array(5,3,4,5));

// assert(6 == $play->countRuns(array(5,3,4,5,'A')));
// assert(8 == $play->countRuns(array(2,3,4,5,5)));
// assert(12 == $play->countRuns(array(5,3,4,5,3)));
// assert(6 == $play->countRuns(array(5,3,4,5,7))); //6
// var_dump($play->getAverageHand());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dealt = array();
    $possible_hands = array();

    for ($i = 1; $i <= TOTAL_NUM_CARDS; $i++) {
        $card = $_POST["card$i"];
        if (!in_array($card, array_keys(Card::$DECK))) {
            throw new Exception("Card #$i value [$card] not valid.");
        }
        $dealt[] = $card;
    }

    function determinePossibleHands($hand) {
        $possible_hands = array();
        $full_pool = $hand;
        for ($i = TOTAL_NUM_CARDS - 1; $i >= 0; $i--) {
            $discard_one = array_pop($hand);
            for ($j = 0; $j < sizeof($hand); $j++) {
                $remaining_hand = $full_pool;
                $discard_two = $hand[$j];
                unset($remaining_hand[$i]);
                unset($remaining_hand[$j]);
                $play = new PotentialPlay();
                $play->discard($discard_one);
                $play->discard($discard_two);
                $play->keep($remaining_hand);
                $possible_hands[] = $play;
                //var_dump($play->getHandValue());
            }
        }
        usort($possible_hands, 'sortPlaysByAverageHand');
        return $possible_hands;
    }

    function sortPlaysByAverageHand($a, $b) {
        return $a->getAverageHand() < $b->getAverageHand();
    }

    $possible_hands = determinePossibleHands($dealt);

}

?>

<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="../cobaltandcurry/css/bootstrap.min.css" />
    <style>
        input {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Enter card values</h1>
    <form method="post" class="form-inline">
        <input type="text" name="card1" class="span1" value="<?= isset($_POST['card1']) ? $_POST['card1'] : '' ?>" />
        <input type="text" name="card2" class="span1" value="<?= isset($_POST['card2']) ? $_POST['card2'] : '' ?>" />
        <input type="text" name="card3" class="span1" value="<?= isset($_POST['card3']) ? $_POST['card3'] : '' ?>" />
        <input type="text" name="card4" class="span1" value="<?= isset($_POST['card4']) ? $_POST['card4'] : '' ?>" />
        <input type="text" name="card5" class="span1" value="<?= isset($_POST['card5']) ? $_POST['card5'] : '' ?>" />
        <input type="text" name="card6" class="span1" value="<?= isset($_POST['card6']) ? $_POST['card6'] : '' ?>" />
        <button type="submit" class="btn">Analyze</button>
    </form>

    <?php if (isset($possible_hands)): ?>
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Hold</th>
                    <th>Discard</th>
                    <th>Average Hand</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($possible_hands as $hand): ?>
                    <tr>
                        <td><?= $hand->getHolds() ?></td>
                        <td><?= $hand->getDiscards() ?></td>
                        <td><?= $hand->getAverageHand() ?></td>
                    </tr>
                <?php endforeach; ?>
            <tbody>
        </table>
    <?php endif; ?>

</div>
</body>
</html>
