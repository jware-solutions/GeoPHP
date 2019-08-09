<?php

namespace JWare\GeoPHP;

use \JWare\GeoPHP\Geometry;
use \JWare\GeoPHP\Point;
use \JWare\GeoPHP\Line;

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
    public function getPoints(): array {
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
        // A polygon has at least three points
        $pointsCount = count($points);
        // '< 4' because the last point has to be the same as first point
        if ($pointsCount < 4) { 
            throw new \Exception("The polygon has to have at least three diferent points", 1);
        }

        // Checks first/last equality
        $lastPosition = $pointsCount - 1;
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
    private function findCentroid(): Point {
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
    private function getSortedVerticies(): array {
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
     * TODO: hacer!!
     */
    public function intersectsLine(Line $line) {
        return false;
    }

    /**
     * To find orientation of ordered triplet (p, q, r). 
     * The function returns following values 
     * @return int 0 --> p, q and r are colinear, 1 --> Clockwise, 2 --> Counterclockwise 
     */
    private function orientation($p, $q, $r) { 
        $val = ($q->getY() - $p->getY()) * ($r->getX() - $q->getX()) - ($q->getX() - $p->getX()) * ($r->getY() - $q->getY()); 
        if ($val == 0) return 0; // colinear 
        return ($val > 0) ? 1 : 2; // clock or counterclock wise 
    }

    /**
     * Given three colinear points p, q, r, the function checks if $q lies on line segment 'pr'
     * @param Point $p Point 1
     * @param Point $q Point 2
     * @param Point $r Point 3
     * @return bool True if if $q lies on line segment 'pr', false otherwise
     */
    private function onSegment(Point $p, Point $q, Point $r) { 
        return ($q->getX() <= max($p->getX(), $r->getX()) && $q->getX() >= min($p->getX(), $r->getX())
            && $q->getY() <= max($p->getY(), $r->getY()) && $q->getY() >= min($p->getY(), $r->getY()));
    } 

    /**
     * Checks if line segment 'p1q1' and 'p2q2' intersect
     * @param Point $p1 Line 1 segment point start
     * @param Point $q1 Line 1 segment point end
     * @param Point $p2 Line 2 segment point start
     * @param Point $q2 Line 2 segment point end
     * @return bool True if intersects, false otherwise
     */
    private function doIntersect(Point $p1, Point $q1, Point $p2, Point $q2) { 
        // Find the four orientations needed for general and 
        // special cases 
        $o1 = $this->orientation($p1, $q1, $p2);
        $o2 = $this->orientation($p1, $q1, $q2);
        $o3 = $this->orientation($p2, $q2, $p1);
        $o4 = $this->orientation($p2, $q2, $q1);

        // General case 
        if ($o1 != $o2 && $o3 != $o4) {
            return true; 
        }
    
        // Special Cases
        // p1, q1 and p2 are colinear and p2 lies on segment p1q1 
        if ($o1 == 0 && $this->onSegment($p1, $p2, $q1)) return true; 
    
        // p1, q1 and p2 are colinear and q2 lies on segment p1q1 
        if ($o2 == 0 && $this->onSegment($p1, $q2, $q1)) return true; 
    
        // p2, q2 and p1 are colinear and p1 lies on segment p2q2 
        if ($o3 == 0 && $this->onSegment($p2, $p1, $q2)) return true; 
    
        // p2, q2 and q1 are colinear and q1 lies on segment p2q2 
        if ($o4 == 0 && $this->onSegment($p2, $q1, $q2)) return true; 
    
        return false; // Doesn't fall in any of the above cases 
    }

    /**
     * Checks if a point is inside a polygon
     * @param Point $point Point to check
     * @return bool True if the point is inside the polygon, false otherwise
     */
    public function pointIsWithin(Point $point) {
        // $this->isInside($poligono, sizeof($poligono), $punto);
        $n = sizeof($this->points);
        $points = $this->points;

        // Create a $for line segment from p to infinite 
        $extremePoint = new Point(10000, $point->getY());
    
        // Count intersections of the above line with sides of polygon 
        $count = 0; $i = 0; 
        do
        { 
            $next = ($i + 1) % $n; 
    
            // Check if the line segment from 'p' to 'extreme' intersects 
            // with the line segment from 'polygon[i]' to 'polygon[next]' 
            if ($this->doIntersect($points[$i], $points[$next], $point, $extremePoint)) { 
                // If the $'p' is colinear with line segment 'i-next', 
                // then check if it lies on segment. If it lies, return true, 
                // otherwise false 
                if ($this->orientation($points[$i], $point, $points[$next]) == 0){
                    return $this->onSegment($points[$i], $point, $points[$next]); 
                }
    
                $count++; 
            } 
            $i = $next; 
        } while ($i != 0); 
    
        // Return true if count is odd, false otherwise 
        return $count % 2 == 1;
    }
}