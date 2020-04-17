<?php

use \JWare\GeoPHP\LineString;
use \JWare\GeoPHP\Polygon;
use \JWare\GeoPHP\Point;
use \JWare\GeoPHP\Line;

class LineStringTest extends \PHPUnit\Framework\TestCase {
	public function testInstantiationOfPolygon() {
        $somePoint = new Point(0, 0);
        $otherPoint = new Point(1, 0);
        $otherPoint2 = new Point(1, 1);
        
        // Checks correct instance
		$this->assertInstanceOf('\JWare\GeoPHP\LineString', new LineString([
            $somePoint,
            $otherPoint,
            $otherPoint2
        ]));
    }

    /**
     * Tests clone method
     */
    public function testClone() {
        $lineString = new LineString([
            new Point(0, 0),
            new Point(4, 0),
            new Point(4, 4),
            new Point(0, 4)
        ]);
        $clone = $lineString->clone();
        $this->assertEquals($clone->getPoints(), $lineString->getPoints());
    }

    /**
     * Tests setPoint method
     */
    public function testSetPoint() {
        $lineString = new LineString([
            new Point(0, 0),
            new Point(4, 0),
            new Point(4, 4),
            new Point(0, 4)
        ]);
        $newPoint = new Point(4, 1);
        $lineString->setPoint(1, $newPoint);
        $this->assertEquals($lineString->getPoints()[1], $newPoint);
    }

    /**
     * Test intersection method
     */
    public function testIntersects() {
        // LineStrings
        $lineString1 = new LineString([
            new Point(2, 4),
            new Point(4, 4),
            new Point(5, 2),
            new Point(3, 1),
            new Point(2.5, 3)
        ]);

        $lineString2 = new LineString([
            new Point(-3, -4),
            new Point(-1, -5),
            new Point(-2, -6),
        ]);

        $lineString3 = new LineString([
            new Point(4, 3),
            new Point(6, 2.5),
            new Point(6, 1.5),
            new Point(4.5, 1.25),
            new Point(4, 3)
        ]);

        $lineString4 = new LineString([
            new Point(-1.5, -5.5),
            new Point(-0.5, -6.5),
            new Point(-2, -7),
            new Point(-1.5, -5.5),
		]);

		// Lines
		$line1 = new Line(
			new Point(-1, -4),
			new Point(-3, -5)
		);

		// Lines
		$line2 = new Line(
			new Point(10, -4),
			new Point(8, -5)
		);
		
		// Polygons
		$polygon1 = new Polygon([
			new Point(1, 2),
			new Point(2, 3),
			new Point(5, 8),
			new Point(6, 1),
			new Point(1, 2)
		]);

		$polygon2 = new Polygon([
			new Point(-1, 2),
			new Point(-2, 3),
			new Point(-5, 8),
			new Point(-6, 1),
			new Point(-1, 2)
		]);

        // With Point
        $this->assertTrue($lineString1->intersectsPoint(new Point(3, 4)));
        $this->assertFalse($lineString1->intersectsPoint(new Point(5, 4)));

		// With Lines
		$this->assertTrue($lineString2->intersectsLine($line1));
        $this->assertFalse($lineString2->intersectsLine($line2));

        // With LineString
        $this->assertTrue($lineString1->intersectsLineString($lineString3));
        $this->assertTrue($lineString4->intersectsLineString($lineString2));
        $this->assertFalse($lineString1->intersectsLineString($lineString2));
		$this->assertFalse($lineString3->intersectsLineString($lineString2));
		
		// With Polygon
        $this->assertTrue($lineString1->intersectsPolygon($polygon1));
        $this->assertFalse($lineString1->intersectsPolygon($polygon2));
    }
}