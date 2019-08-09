<?php

use \JWare\GeoPHP\Line;
use \JWare\GeoPHP\Point;

class LineTest extends \PHPUnit\Framework\TestCase {
	public function testInstantiationOfLine() {
		$this->assertInstanceOf('\JWare\GeoPHP\Line', new Line(
            new Point(0, 0),
            new Point(1, 1)
        ));
    }

    /**
     * Tests the determinant of the line
     */
    public function testDeterminant() {
        $line1 = new Line(
            new Point(1.34, 2.87),
            new Point(3.23, 4.5)
        );

        $line2 = new Line(
            new Point(3.23, 4.5),
            new Point(1.34, 2.87)
        );

        $this->assertEquals(-3.2401, $line1->determinant());

        // NOTE: Line(a, b).determinant() == -Line(b, a).determinant()
        $this->assertEquals($line1->determinant(), -$line2->determinant());
    }

    /**
     * Tests the difference in 'x' components (Δx).
     */
    public function testDx() {
        $line1 = new Line(
            new Point(2.77, 4.23),
            new Point(2.77, 7.23)
        );

        $line2 = new Line(
            new Point(2.77, 4.23),
            new Point(3.77, 7.23)
        );

        $this->assertEquals(0, $line1->dx());

        $this->assertEquals(1, $line2->dx());
    }

    /**
     * Tests the difference in 'y' components (Δy).
     */
    public function testDy() {
        $line1 = new Line(
            new Point(2.77, 4.23),
            new Point(2.77, 7.23)
        );

        $line2 = new Line(
            new Point(2.77, 14.32),
            new Point(3.77, 9.55)
        );

        $this->assertEquals(3, $line1->dy());

        $this->assertEquals(-4.77, $line2->dy());
    }

    /**
     * Tests the slope of a line
     */
    public function testSlope() {
        $line1 = new Line(
            new Point(2.87, 4.23),
            new Point(2.77, 7)
        );

        $line2 = new Line(
            new Point(3.45, 11.3),
            new Point(0.87, 34.243)
        );

        $line3 = new Line(
            new Point(0.87, 34.243),
            new Point(3.45, 11.3)
        );

        $line4 = new Line(
            new Point(2, 34),
            new Point(2, 11)
        );

        $this->assertEquals(-27.7, $line1->slope());
        $this->assertEquals(-8.8926356589147, $line2->slope());
        
        // NOTE: Line(a, b).slope() == Line(b, a).slope()
        $this->assertEquals($line2->slope(), $line3->slope());
        $this->assertEquals(INF, $line4->slope());
    }

    /**
     * Test intersection method
     */
    public function testIntersects() {
        $line1 = new Line(
            new Point(1, 1),
            new Point(5, 5)
        );

        $line2 = new Line(
            new Point(3, 3),
            new Point(5, 1)
        );

        $line3 = new Line(
            new Point(-1, 1),
            new Point(-5, -10)
        );

        $point1 = new Point(3, 3);
        $point2 = new Point(-1, 1);
        $point3 = new Point(-5, -10);

        $this->assertTrue($line1->intersectsLine($line2));
        $this->assertTrue($line2->intersectsLine($line1));
        $this->assertTrue($line1->intersectsLine($line1));
        $this->assertFalse($line1->intersectsLine($line3));
        $this->assertFalse($line2->intersectsLine($line3));
        $this->assertFalse($line3->intersectsLine($line2));
        $this->assertTrue($line1->intersectsPoint($point1));
        $this->assertTrue($line2->intersectsPoint($point1));
        $this->assertFalse($line3->intersectsPoint($point1));
        $this->assertTrue($line3->intersectsPoint($point2));
        $this->assertTrue($line3->intersectsPoint($point3));

        // TODO: itersects polygon
    }
}

?>