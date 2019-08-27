<?php

namespace JWare\GeoPHP;

use \JWare\GeoPHP\Point;

/**
 * Represents a line segment made up of exactly two Points.
 */
class Line {
    private $start;
    private $end;

    public function __construct(Point $start, Point $end) {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * Clone method
     * @return Line New instance
     */
    public function clone() {
        return new Line($this->start, $this->end);
    }

    /**
     * Getter of the starting point of the line
     * @return Point starting point of the line
     */
    public function getStart(): Point {
        return $this->start;
    }

    /**
     * Setter of the starting point of the line
     * @param Point $start starting point of the line
     * @return Line Current instance
     */
    public function setStart(Point $start): Line {
        $this->start = $start;
        return $this;
    }

    /**
     * Getter of the ending point of the line
     * @return Point ending point of the line
     */
    public function getEnd(): Point {
        return $this->end;
    }

    /**
     * Setter of the ending point of the line
     * @param Point $end ending point of the line
     * @return Line Instance
     */
    public function setEnd(Point $end): Line {
        $this->end = $end;
        return $this;
    }

    /**
     * Calculates the determinant of the line:
     * line.start.x * line.end.y - line.start.y * line.end.x
     * @return float The determinant of the line
     */
    public function determinant(): float {
        return $this->start->getX() * $this->end->getY() - $this->start->getY() * $this->end->getX();
    }

    /**
     * Calculates the difference in 'x' components (Δx):
     * line.end.x - line.start.x
     * @return float The difference in 'x' components (Δx)
     */
    public function dx(): float {
        return $this->end->getX() - $this->start->getX();
    }

    /**
     * Calculates the difference in 'y' components (Δy):
     * line.end.y - line.start.y
     * @return float The difference in 'y' components (Δy)
     */
    public function dy(): float {
        return $this->end->getY() - $this->start->getY();
    }

    /**
     * Calculates the slope of a line:
     * line.dy() / line.dx()
     * @return float The slope of a line
     */
    public function slope(): float {
        $dx = $this->dx();
        return ($dx != 0) ? $this->dy() / $dx : INF;
    }

    /**
     * Getter for starting and ending points
     * @return float[] Array with the starting point (0) and the ending point (1)
     */
    public function getPoints(): array {
        return [$this->start, $this->end];
    }

    /**
     * Checks whether the line intersects a point
     * @param Point $point Point to check
     * @return bool True if the line intersects with the point, false otherwise
     */
    public function intersectsPoint(Point $point): bool {
        $dx = $this->dx();
        $tx = ($dx != 0) ? ($point->getX() - $this->start->getX()) / $this->dx() : null;

        $dy = $this->dy();
        $ty = ($dy != 0) ? ($point->getY() - $this->start->getY()) / $this->dy() : null;

        $txIsNull = is_null($tx);
        $tyIsNull = is_null($ty);

        // If I don't have $tx nor $ty
        if ($txIsNull && $tyIsNull) {
            return $point->isEqual($this->start);
        }

        // If I have $tx
        if (!$txIsNull && $tyIsNull) {
            return $point->getY() == $this->start->getY() && 0 <= $tx && $tx <= 1;
        }
        
        // If I have $ty
        if ($txIsNull && !$tyIsNull) {
            return $point->getX() == $this->start->getX() && 0 <= $ty && $ty <= 1;
        }

        // If I have $tx and $ty...
        return abs($tx - $ty) <= 0.000001 && 0 <= $tx && $tx <= 1;
    }

    /**
     * Checks whether the line intersects another line
     * @param Line $line Line to check
     * @return bool True if the line intersects with the point, false otherwise
     */
    public function intersectsLine(Line $line): bool {
        // Using Cramer's Rule:
        // https://en.wikipedia.org/wiki/Intersection_%28Euclidean_geometry%29#Two_line_segments
        $a1 = $this->dx();
        $a2 = $this->dy();
        $b1 = -$line->dx();
        $b2 = -$line->dy();
        $c1 = $line->start->getX() - $this->start->getX();
        $c2 = $line->start->getY() - $this->start->getY();

        $d = $a1 * $b2 - $a2 * $b1;
        if (!$d) {
            $thisPoints = $this->getPoints();
            $selfStartPoint = $thisPoints[0];
            $selfEndPoint = $thisPoints[1];
            $otherPoints = $line->getPoints();
            $otherStartPoint = $otherPoints[0];
            $otherEndPoint = $otherPoints[1];

            // Lines are parallel
            // Return true if at least one end point intersects the other line
            return $selfStartPoint->intersectsLine($line)
                || $selfEndPoint->intersectsLine($line)
                || $otherStartPoint->intersectsLine($this)
                || $otherEndPoint->intersectsLine($this);
        }

        $s = ($c1 * $b2 - $c2 * $b1) / $d;
        $t = ($a1 * $c2 - $a2 * $c1) / $d;
        return (0 <= $s) && ($s <= 1) && (0 <= $t) && ($t <= 1);
    }

    /**
     * Checks whether the line intersects a polygon
     * @param Polygon $polygon Polygon to check
     * @return bool True if the line intersects with the polygon, false otherwise
     */
    public function intersectsPolygon(Polygon $polygon): bool {
        return $polygon->intersectsLine($this);
    }
}