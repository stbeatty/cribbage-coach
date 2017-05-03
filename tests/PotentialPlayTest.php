<?php

use PHPUnit\Framework\TestCase;

final class PotentialPlayTest extends TestCase {

    public function testCountFifteens() {

        $this->assertMethod('countFifteens', 8, ['4D','5H','5C','JC','AC']);
        $this->assertMethod('countFifteens', 4, ['4D','5H','5C','JC','2C']);
        $this->assertMethod('countFifteens', 4, ['4D','5H','5C','JC','3C']);
        $this->assertMethod('countFifteens', 4, ['4D','5H','5C','JC','4C']);
        $this->assertMethod('countFifteens', 8, ['4D','5H','5C','JC','5C']);

        $this->assertMethod('countFifteens', 4, ['2C','3C','4D','JC','6C']);
        $this->assertMethod('countFifteens', 2, ['2C','3C','4D','JC','7C']);
        $this->assertMethod('countFifteens', 4, ['2C','3C','4D','JC','8C']);
        $this->assertMethod('countFifteens', 4, ['2C','3C','4D','JC','9C']);
        $this->assertMethod('countFifteens', 4, ['2C','3C','4D','JC','10C']);
    }

    public function testCountPairs() {
        $this->assertMethod('countPairs', 2, ['4D','5H','5C','JC','AC']);
        $this->assertMethod('countPairs', 2, ['4D','5H','5C','JC','2C']);
        $this->assertMethod('countPairs', 2, ['4D','5H','5C','JC','3C']);
        $this->assertMethod('countPairs', 4, ['4D','5H','5C','JC','4C']);
        $this->assertMethod('countPairs', 6, ['4D','5H','5C','JC','5C']);

        $this->assertMethod('countPairs', 0, ['2C','3C','4D','JC','6C']);
        $this->assertMethod('countPairs', 0, ['2C','3C','4D','JC','7C']);
        $this->assertMethod('countPairs', 0, ['2C','3C','4D','JC','8C']);
        $this->assertMethod('countPairs', 0, ['2C','3C','4D','JC','9C']);
        $this->assertMethod('countPairs', 0, ['2C','3C','4D','JC','10C']);
    }

    public function testCountRuns() {
        $this->assertMethod('countRuns', 0, ['4D','5H','5C','JC','AC']);
        $this->assertMethod('countRuns', 0, ['4D','5H','5C','JC','2C']);
        $this->assertMethod('countRuns', 6, ['4D','5H','5C','JC','3C']);
        $this->assertMethod('countRuns', 0, ['4D','5H','5C','JC','4C']);
        $this->assertMethod('countRuns', 0, ['4D','5H','5C','JC','5C']);

        $this->assertMethod('countRuns', 4, ['2C','3C','4D','JC','AC']);
        $this->assertMethod('countRuns', 6, ['2C','3C','4D','JC','2C']);
        $this->assertMethod('countRuns', 6, ['2C','3C','4D','JC','3C']);
        $this->assertMethod('countRuns', 6, ['2C','3C','4D','JC','4C']);
        $this->assertMethod('countRuns', 4, ['2C','3C','4D','JC','5C']);
        $this->assertMethod('countRuns', 3, ['2C','3C','4D','JC','6C']);
        $this->assertMethod('countRuns', 3, ['2C','3C','4D','JC','7C']);
        $this->assertMethod('countRuns', 3, ['2C','3C','4D','JC','8C']);
        $this->assertMethod('countRuns', 3, ['2C','3C','4D','JC','9C']);
        $this->assertMethod('countRuns', 3, ['2C','3C','4D','JC','10C']);
    }

    public function testCalculateAverageHand() {
        $p = new PotentialPlay();
        $p->discard(['JC','2C'])->keep(['3C','4D','5H','5C']);
        $this->assertEquals(16.32, $p->calculateAverageHand());

        $p = new PotentialPlay();
        $p->discard(['5H','5C'])->keep(['2C','3C','4D','JC']);
        $this->assertEquals(16.92, $p->calculateAverageHand());

        $p = new PotentialPlay();
        $p->discard(['4D','5H'])->keep(['2C','3C','5C','JC']);
        $this->assertEquals(17.96, $p->calculateAverageHand());
    }

    private function assertMethod($method, $expectedValue, $cards) {
        $p = new PotentialPlay();
        $hand = [];
        foreach ($cards as $card) {
            $hand[] = new Card($card);
        }
        $this->assertEquals($expectedValue, $p->$method($hand));
    }

}
