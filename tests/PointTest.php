<?php

use \Genarito\GeoPHP\Point;

class PointTest extends \PHPUnit\Framework\TestCase {
	public function testInstantiationOfPoint() {
		$this->assertInstanceOf('\Genarito\GeoPHP\Point', new Point());
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
        $this->assertInstanceOf('\Genarito\GeoPHP\Point', $point1Converted);
        $this->assertInstanceOf('\Genarito\GeoPHP\Point', $point2Converted);

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
        // Todo
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
        $point1 = new Point(2.0, 4.5);
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
        $this->assertEquals(2.0, $cross);
    }
}

?>