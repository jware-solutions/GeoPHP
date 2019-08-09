<?php

use \JWare\GeoPHP\Polygon;
use \JWare\GeoPHP\Point;

class PolygonTest extends \PHPUnit\Framework\TestCase {
	public function testInstantiationOfPolygon() {
        $somePoint = new Point(0, 0);
        $otherPoint = new Point(1, 0);
        $otherPoint2 = new Point(1, 1);
        
        // Checks correct instance
		$this->assertInstanceOf('\JWare\GeoPHP\Polygon', new Polygon([
            $somePoint,
            $otherPoint,
            $otherPoint2,
            $somePoint
        ]));

        // Checks invalid Polygon
        $this->expectException(\Exception::class);
        $invalidPolygon = new Polygon([
            new Point(0, 1),
            new Point(3, 1),
            new Point(4, 2),
            new Point(1, 1) // Not equals to first point
        ]);

        // Has only 2 different points
        $invalidPolygon2 = new Polygon([
            new Point(0, 1),
            new Point(3, 1),
            new Point(1, 1) 
        ]);
    }

    /**
     * Test the area computation
     */
    public function testArea() {
        $polygon1 = new Polygon([
            new Point(0, 0),
            new Point(4, 0),
            new Point(4, 4),
            new Point(0, 4),
            new Point(0, 0),
        ]);

        $polygon2 = new Polygon([
            new Point(0, 0),
            new Point(4, 0),
            new Point(2, 4),
            new Point(0, 0)
        ]);

        $this->assertEquals(16, $polygon1->area());
        $this->assertEquals(8, $polygon2->area());
    }

    /**
     * Test if point is within a polygon
     */
    public function testPointIsWithin() {
        $polygon1 = new Polygon([
            new Point(0, 0),
            new Point(4, 0),
            new Point(4, 4),
            new Point(0, 4),
            new Point(0, 0),
        ]);

        $this->assertTrue($polygon1->pointIsWithin(new Point(2, 2)));
        $this->assertTrue($polygon1->pointIsWithin(new Point(4, 4)));
        $this->assertTrue($polygon1->pointIsWithin(new Point(0, 2)));
        $this->assertFalse($polygon1->pointIsWithin(new Point(10, 12)));
        $this->assertFalse($polygon1->pointIsWithin(new Point(-1, 2)));
        $this->assertFalse($polygon1->pointIsWithin(new Point(5, -2)));
    }
}