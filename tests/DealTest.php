<?php

use PHPUnit\Framework\TestCase;

final class DealTest extends TestCase {

    public function testConstruct() {
        $d = new Deal([
            'card0' => '2C',
            'card1' => '3C',
            'card2' => '4D',
            'card3' => '5H',
            'card4' => '5C',
            'card5' => 'JC',
            'dealer' => 'self'
        ]);
        $this->assertEquals(Deal::$total_num_cards, count($d->hand));
    }

    public function testBadConstruct_int() {
        $this->expectException(InvalidArgumentException::class);
        $d = new Deal(1);
    }

    public function testBadConstruct_tooShort() {
        $this->expectException(Exception::class);
        $d = new Deal([
            'card0' => '2C',
            'card1' => '3C',
            'card2' => '4D',
            'card3' => '5H',
            'card4' => '5C'
        ]);
    }

}
