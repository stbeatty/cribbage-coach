<?php

use PHPUnit\Framework\TestCase;

final class PotentialPlayTest extends TestCase {
    
    public function testAddToDiscards() {
        $potentialPlay = new PotentialPlay();
        $discard = 'J';
        $this->assertEquals(
            $potentialPlay->getDiscards(),
            ''
        );
        $potentialPlay->discard($discard);
        $this->assertEquals(
            $potentialPlay->getDiscards(),
            $discard
        );
    }

    public function testCountFifteens() {
        $p = new PotentialPlay();
        $this->assertEquals(8, $p->countFifteens(['4','5','5','J','A']));
        $this->assertEquals(4, $p->countFifteens(['4','5','5','J','2']));
        $this->assertEquals(4, $p->countFifteens(['4','5','5','J','3']));
        $this->assertEquals(4, $p->countFifteens(['4','5','5','J','4']));
        $this->assertEquals(8, $p->countFifteens(['4','5','5','J','5']));

        $this->assertEquals(4, $p->countFifteens(['2','3','4','J','6']));
        $this->assertEquals(2, $p->countFifteens(['2','3','4','J','7']));
        $this->assertEquals(4, $p->countFifteens(['2','3','4','J','8']));
        $this->assertEquals(4, $p->countFifteens(['2','3','4','J','9']));
        $this->assertEquals(4, $p->countFifteens(['2','3','4','J','10']));
    }

    public function testCountPairs() {
        $p = new PotentialPlay();
        $this->assertEquals(2, $p->countPairs(['4','5','5','J','A']));
        $this->assertEquals(2, $p->countPairs(['4','5','5','J','2']));
        $this->assertEquals(2, $p->countPairs(['4','5','5','J','3']));
        $this->assertEquals(4, $p->countPairs(['4','5','5','J','4']));
        $this->assertEquals(6, $p->countPairs(['4','5','5','J','5']));

        $this->assertEquals(0, $p->countPairs(['2','3','4','J','6']));
        $this->assertEquals(0, $p->countPairs(['2','3','4','J','7']));
        $this->assertEquals(0, $p->countPairs(['2','3','4','J','8']));
        $this->assertEquals(0, $p->countPairs(['2','3','4','J','9']));
        $this->assertEquals(0, $p->countPairs(['2','3','4','J','10']));
    }

    public function testCountRuns() {
        $p = new PotentialPlay();
        $this->assertEquals(0, $p->countRuns(['4','5','5','J','A']));
        $this->assertEquals(0, $p->countRuns(['4','5','5','J','2']));
        $this->assertEquals(6, $p->countRuns(['4','5','5','J','3']));
        $this->assertEquals(0, $p->countRuns(['4','5','5','J','4']));
        $this->assertEquals(0, $p->countRuns(['4','5','5','J','5']));

        $this->assertEquals(4, $p->countRuns(['2','3','4','J','A']));
        $this->assertEquals(6, $p->countRuns(['2','3','4','J','2']));
        $this->assertEquals(6, $p->countRuns(['2','3','4','J','3']));
        $this->assertEquals(6, $p->countRuns(['2','3','4','J','4']));
        $this->assertEquals(4, $p->countRuns(['2','3','4','J','5']));
        $this->assertEquals(3, $p->countRuns(['2','3','4','J','6']));
        $this->assertEquals(3, $p->countRuns(['2','3','4','J','7']));
        $this->assertEquals(3, $p->countRuns(['2','3','4','J','8']));
        $this->assertEquals(3, $p->countRuns(['2','3','4','J','9']));
        $this->assertEquals(3, $p->countRuns(['2','3','4','J','10']));
    }
}
