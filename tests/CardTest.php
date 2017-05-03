<?php

use PHPUnit\Framework\TestCase;

final class CardTest extends TestCase {

    public function testConstruct() {
        $c = new Card('AD');
        $this->assertEquals('A', $c->getFaceValue());
        $this->assertEquals('D', $c->getSuit());
        $this->assertEquals(1, $c->getValue());

        $c = new Card('JS');
        $this->assertEquals('J', $c->getFaceValue());
        $this->assertEquals('S', $c->getSuit());
        $this->assertEquals(10, $c->getValue());

        $c = new Card('10H');
        $this->assertEquals('10', $c->getFaceValue());
        $this->assertEquals('H', $c->getSuit());
        $this->assertEquals(10, $c->getValue());
    }

    public function testBadConstruct_int() {
        $this->expectException(InvalidArgumentException::class);
        $c = new Card(1);
    }

    public function testBadConstruct_blank() {
        $this->expectException(Exception::class);
        $c = new Card('');
    }

    public function testBadConstruct_tooShort() {
        $this->expectException(Exception::class);
        $c = new Card('0');
    }

    public function testBadConstruct_tooLong() {
        $this->expectException(Exception::class);
        $c = new Card('0123');
    }

    public function testBadConstruct_badFaceValue() {
        $this->expectException(Exception::class);
        $c = new Card('MH');
    }

    public function testBadConstruct_badSuitValue() {
        $this->expectException(Exception::class);
        $c = new Card('AA');
    }

}
