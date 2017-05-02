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
}
