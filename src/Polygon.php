<?php

namespace Genarito\GeoPHP;

use \Genarito\GeoPHP\Geometry;
use \Genarito\GeoPHP\Line;

/**
 * Represents a single point in 2D space.
 */
class Polygon implements Geometry {
    private $points;

    /**
     * Constructor
     * @param Point[] $points Array of points that make up the polygon
     * @return Polygon New instance
     */
    public function __construct(array $points) {
        // First and last point must have the same values
        $this->setPoints($points);
    }

    /**
     * Getter of the points of the polygon
     * @return Point[] Array of points that make up the polygon
     */
    public function getPoint(): array {
        return $this->points;
    }

    /**
     * Setter for one point of the polygon
     * @param int $idx Index in the array of the polygon to replace
     * @param Point $point Point to put in the specified position
     * @return Polygon Current instance
     */
    public function setPoint(int $idx, Point $point): Polygon {
        $this->points[$idx] = $point;
        return $this;
    }

    /**
     * Setter for all the points of the polygon
     * @param Point[] $points Array of points that make up the polygon
     * @return Polygon Current instance
     */
    public function setPoints(array $points): Polygon {
        $lastPosition = count($points) - 1;
        if (!$points[0]->isEqual($points[$lastPosition])) {
            throw new \Exception("First and last point must have the same values", 1);
        }

        $this->points = $points;
        return $this;
    }

    /**
     * Computes the polygon's centroid
     * @return Point Polygon's centroid point
     */
    private function findCentroid() {
        $x = 0.0;
        $y = 0.0;
        foreach ($this->points as $point) {
            $x += $point->getX();
            $y += $point->getY();
        }

        $pointsCount = count($this->points);
        return new Point(
            $x / $pointsCount,
            $y / $pointsCount
        );
    }

    /**
     * Sorts the polygon's vertices in clockwise order
     * @return Point[] Array with the sorted vertices
     */
    private function getSortedVerticies() {
        // Gets polygon centroid
        $center = $this->findCentroid();
        $subArray = array_slice($this->points, 0, count($this->points) - 1);
        usort($subArray, function($point1, $point2) use ($center) {
            $a1 = (rad2deg(atan2($point1->getX() - $center->getX(), $point1->getY() - $center->getY())) + 360) % 360;
            $a2 = (rad2deg(atan2($point2->getX() - $center->getX(), $point2->getY() - $center->getY())) + 360) % 360;
            return $a2 - $a1;
        });
        $subArray[] = $subArray[0];
        return $subArray;
    }

    /**
     * Abstract method implementation
     * Uses the Shoelace Formula:
     * https://en.wikipedia.org/wiki/Shoelace_formula
     */
    public function area() {
        // Initialze area 
        $area = 0.0;

        // Sorted vertices are required
        $sortedVertices = $this->getSortedVerticies();
    
        // Calculate value of Shoelace formula 
        $j = $n = count($sortedVertices) - 1;
        for ($i = 0; $i <= $n; $i++) {
            $point1 = $sortedVertices[$i];
            $point2 = $sortedVertices[$j];
            $area += ($point2->getX() + $point1->getX()) * ($point2->getY() - $point1->getY());
            // j is previous vertex to i
            $j = $i;
        } 
    
        // Return absolute value
        return abs($area / 2.0);
    }

    /**
     * Abstract method implementation
     */
    public function intersects(Geometry $otherGeometry): bool {
        $class = get_class($otherGeometry);
        switch ($class) {
            case Point::class:
                break;
            case Line::class:
                // Uses double dispatching
                break;
            default:
                throw new \Exception("Not valid geometry", 1);
                break;
        }

        return $intersects;
    }
}