<?php

use PHPUnit\Framework\TestCase;

final class PotentialPlayTest extends TestCase {

    public function testGetCardFrequency() {
        $p = new PotentialPlay();
        $p->discard([new Card('2C'), new Card('JC')])
          ->keep([new Card('3C'), new Card('4D'), new Card('5H'), new Card('5C')]);

        $this->assertEquals(4, $p->getCardFrequency(new Card('AN')));
        $this->assertEquals(3, $p->getCardFrequency(new Card('2N')));
        $this->assertEquals(3, $p->getCardFrequency(new Card('3N')));
        $this->assertEquals(3, $p->getCardFrequency(new Card('4N')));
        $this->assertEquals(2, $p->getCardFrequency(new Card('5N')));
        $this->assertEquals(4, $p->getCardFrequency(new Card('6N')));
        $this->assertEquals(4, $p->getCardFrequency(new Card('7N')));
        $this->assertEquals(4, $p->getCardFrequency(new Card('8N')));
        $this->assertEquals(4, $p->getCardFrequency(new Card('9N')));
        $this->assertEquals(4, $p->getCardFrequency(new Card('10N')));
        $this->assertEquals(3, $p->getCardFrequency(new Card('JN')));
        $this->assertEquals(4, $p->getCardFrequency(new Card('QN')));
        $this->assertEquals(4, $p->getCardFrequency(new Card('KN')));
    }

    public function testCountFifteens() {
        $this->assertMethod('countFifteens', 8, ['4D','5H','5C','JC','AN']);
        $this->assertMethod('countFifteens', 4, ['4D','5H','5C','JC','2N']);
        $this->assertMethod('countFifteens', 4, ['4D','5H','5C','JC','3N']);
        $this->assertMethod('countFifteens', 4, ['4D','5H','5C','JC','4N']);
        $this->assertMethod('countFifteens', 8, ['4D','5H','5C','JC','5N']);

        $this->assertMethod('countFifteens', 4, ['2C','3C','4D','JC','6N']);
        $this->assertMethod('countFifteens', 2, ['2C','3C','4D','JC','7N']);
        $this->assertMethod('countFifteens', 4, ['2C','3C','4D','JC','8N']);
        $this->assertMethod('countFifteens', 4, ['2C','3C','4D','JC','9N']);
        $this->assertMethod('countFifteens', 4, ['2C','3C','4D','JC','10N']);
    }

    public function testCountPairs() {
        $this->assertMethod('countPairs', 2, ['4D','5H','5C','JC','AN']);
        $this->assertMethod('countPairs', 2, ['4D','5H','5C','JC','2N']);
        $this->assertMethod('countPairs', 2, ['4D','5H','5C','JC','3N']);
        $this->assertMethod('countPairs', 4, ['4D','5H','5C','JC','4N']);
        $this->assertMethod('countPairs', 6, ['4D','5H','5C','JC','5N']);

        $this->assertMethod('countPairs', 0, ['2C','3C','4D','JC','6N']);
        $this->assertMethod('countPairs', 0, ['2C','3C','4D','JC','7N']);
        $this->assertMethod('countPairs', 0, ['2C','3C','4D','JC','8N']);
        $this->assertMethod('countPairs', 0, ['2C','3C','4D','JC','9N']);
        $this->assertMethod('countPairs', 0, ['2C','3C','4D','JC','10N']);
    }

    public function testCountRuns() {
        $this->assertMethod('countRuns', 0, ['4D','5H','5C','JC','AN']);
        $this->assertMethod('countRuns', 0, ['4D','5H','5C','JC','2N']);
        $this->assertMethod('countRuns', 6, ['4D','5H','5C','JC','3N']);
        $this->assertMethod('countRuns', 0, ['4D','5H','5C','JC','4N']);
        $this->assertMethod('countRuns', 0, ['4D','5H','5C','JC','5N']);

        $this->assertMethod('countRuns', 4, ['2C','3C','4D','JC','AN']);
        $this->assertMethod('countRuns', 6, ['2C','3C','4D','JC','2N']);
        $this->assertMethod('countRuns', 6, ['2C','3C','4D','JC','3N']);
        $this->assertMethod('countRuns', 6, ['2C','3C','4D','JC','4N']);
        $this->assertMethod('countRuns', 4, ['2C','3C','4D','JC','5N']);
        $this->assertMethod('countRuns', 3, ['2C','3C','4D','JC','6N']);
        $this->assertMethod('countRuns', 3, ['2C','3C','4D','JC','7N']);
        $this->assertMethod('countRuns', 3, ['2C','3C','4D','JC','8N']);
        $this->assertMethod('countRuns', 3, ['2C','3C','4D','JC','9N']);
        $this->assertMethod('countRuns', 3, ['2C','3C','4D','JC','10N']);
        $this->assertMethod('countRuns', 8, ['3C','4C','5H','5C','2N']);
        $this->assertMethod('countRuns', 6, ['3C','4D','5H','5C','JN']);
    }

    public function testCalculateAverageHand() {
        $p = new PotentialPlay();
        $p->discard([new Card('JC'), new Card('2C')])
          ->keep([new Card('3C'), new Card('4D'), new Card('5H'), new Card('5C')]);
        $this->assertEquals(16.32, $p->getExpectedAverageSelf());

        $p = new PotentialPlay();
        $p->discard([new Card('5H'), new Card('5C')])
          ->keep([new Card('2C'), new Card('3C'), new Card('4D'), new Card('JC')]);
        $this->assertEquals(16.92, $p->getExpectedAverageSelf());

        $p = new PotentialPlay();
        $p->discard([ new Card('4D'), new Card('5H')])
          ->keep([new Card('2C'), new Card('3C'), new Card('5C'), new Card('JC')]);
        $this->assertEquals(17.96, $p->getExpectedAverageSelf());
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
