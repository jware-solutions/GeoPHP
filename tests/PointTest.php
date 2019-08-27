<?php

use \JWare\GeoPHP\Point;
use \JWare\GeoPHP\Line;
use \JWare\GeoPHP\Polygon;

class PointTest extends \PHPUnit\Framework\TestCase {
	public function testInstantiationOfPoint() {
		$this->assertInstanceOf('\JWare\GeoPHP\Point', new Point(0, 0));
    }

    /**
     * Tests clone method
     */
    public function testClone() {
        $point = new Point(3, 4);
        $clone = $point->clone();
        $this->assertEquals($clone->getXAndY(), $point->getXandY());
    }

    /**
     * Tests X and Y getter
     */
    public function testGetXandY() {
        $point = new Point(3, 4);
        $this->assertEquals([3, 4], $point->getXandY());
    }

    /**
     * Tests whether two points are the same
     */
    public function testIsEqual() {
        $point1 = new Point(1, 2);
        $point2 = new Point(1, 2);
        $point3 = new Point(3, 23.3);

        $this->assertTrue($point1->isEqual($point1));
        $this->assertTrue($point1->isEqual($point2));
        $this->assertTrue($point2->isEqual($point1));
        $this->assertFalse($point2->isEqual($point3));
    }

    /**
     * Test magnitude computation
     */
    public function testGetMagnitude() {
        $magnitude1 = (new Point(0, 0))->getMagnitude();
        $this->assertEquals(0, $magnitude1);
        $magnitude2 = (new Point(1, 2))->getMagnitude();
        $this->assertEquals(2.236067977, round($magnitude2, $precision=9));
    }

    /**
     * Test conversion from radians to degrees
     */
    public function fromRadiansToDegrees() {
        $point1 = new Point(1, 0);
        $point2 = new Point(2, 3);
        $point1Converted = $point1->fromRadiansToDegrees();
        $point2Converted = $point2->fromRadiansToDegrees();

        // Converted instances are also Points
        $this->assertInstanceOf('\JWare\GeoPHP\Point', $point1Converted);
        $this->assertInstanceOf('\JWare\GeoPHP\Point', $point2Converted);

        // Checks converted values
        $this->assertEquals(57.29578, $point1Converted->getX());
        $this->assertEquals(0, $point1Converted->getY());
        $this->assertEquals(114.5916, $point2Converted->getX());
        $this->assertEquals(171.8873, $point2Converted->getY());
    }

    /**
     * Test conversion from degrees to radians
     */
    public function fromDegreesToRadians() {
        $point1 = new Point(57.29578, 0);
        $point2 = new Point(114.5916, 171.8873);
        $point1Converted = $point1->fromDegreesToRadians();
        $point2Converted = $point2->fromDegreesToRadians();

        // Converted instances are also Points
        $this->assertInstanceOf('\JWare\GeoPHP\Point', $point1Converted);
        $this->assertInstanceOf('\JWare\GeoPHP\Point', $point2Converted);

        // Checks converted values
        $this->assertEquals(1, $point1Converted->getX());
        $this->assertEquals(0, $point1Converted->getY());
        $this->assertEquals(2, $point2Converted->getX());
        $this->assertEquals(3, $point2Converted->getY());
    }

    /**
     * Tests getting the angle between two points
     */
    public function testGetAngle() {
        $point1 = new Point(2, 0);
        $point2 = new Point(2, 2);
        $point3 = new Point(0, 2);

        $this->assertEquals(45, $point1->getAngle($point2));
        $this->assertEquals(45, $point2->getAngle($point3));
        $this->assertEquals(90, $point1->getAngle($point3));
    }
    
    /**
     * Tests the dot product of the two points:
     * dot = x1 * x2 + y1 * y2
     */
    public function testDotProduct() {
        $point1 = new Point(2, 4.5);
        $dot = $point1->dotProduct(new Point(1.5, 0.5));
        $this->assertEquals(5.25, $dot);
    }

    /**
     * Tests the cross product of three point
     */
    public function testCrossProduct() {
        $pointA = new Point(1, 2);
        $pointB = new Point(3, 5);
        $pointC = new Point(7, 12);

        $cross = $pointA->crossProduct($pointB, $pointC);
        $this->assertEquals(2, $cross);
    }

    /**
     * Tests the euclidean distance between two points
     */
    public function testEuclideanDistance() {
        $point1 = new Point(1, 2);
        $this->assertEquals(4.243, round($point1->euclideanDistance(new Point(4, 5)), $precision=3),);
        
        $point2 = new Point(1, 4);
        $this->assertEquals(1.414, round($point2->euclideanDistance(new Point(2, 5)), $precision=3),);
    }

    /**
     * Test intersection method
     */
    public function testIntersects() {
        // Lines
        $line1 = new Line(
            new Point(1, 1),
            new Point(5, 5)
        );

        // Points
        $point1 = new Point(3, 3);
        $point2 = new Point(3, 3);
        $point3 = new Point(3, 4);
        $point4 = new Point(5, 5);

        // Polygons
        $polygon = new Polygon([
            new Point(1, 1),
            new Point(3, 3),
            new Point(2, 7),
            new Point(4, 5),
            new Point(7, 5),
            new Point(1, 1)
        ]);

        // With Point
        $this->assertTrue($point1->intersectsPoint($point2));
        $this->assertTrue($point2->intersectsPoint($point1));
        $this->assertFalse($point1->intersectsPoint($point3));

        // With Line
        $this->assertTrue($point1->intersectsLine($line1));
        $this->assertTrue($point4->intersectsLine($line1)); // Extreme
        $this->assertFalse($point3->intersectsLine($line1));

        // With Polygon
        // $this->assertTrue($point1->intersectsPolygon($polygon));
        // $this->assertTrue($point4->intersectsPolygon($polygon));
        $this->assertFalse($point3->intersectsPolygon($polygon));
    }
}

?>