<?php

namespace JWare\GeoPHP;

use \JWare\GeoPHP\Point;
use \JWare\GeoPHP\Line;
use \JWare\GeoPHP\Exceptions\NotEnoughPointsException;
use \JWare\GeoPHP\Exceptions\FirstAndLastPointNotEqualException;
use \JWare\GeoPHP\Exceptions\SettingPointException;

/**
 * An ordered collection of two or more Points, representing a path between locations.
 */
class LineString {
    private $points;

    /**
     * Constructor
     * @param Point[] $points Array of points that make up the LineString
     * @return LineString New instance
     */
    public function __construct(array $points) {
        $this->points = $points;
        return $this;
    }

    /**
     * Clone method
     * @return LineString New instance
     */
    public function clone() {
        return new LineString($this->points);
    }

    /**
     * Getter of the points of the LineString
     * @return Point[] Array of points that make up the LineString
     */
    public function getPoints(): array {
        return $this->points;
	}
	
	/**
     * Generates an iterator of the Polygon lines formed by
     * all its Points
     * @return Iterator Iterator of the line
     */
    private function getLines() {
		$n = sizeof($this->points);
        $linePoints = $this->points;
		$i = 0;
        do {
            $next = ($i + 1) % $n; 
            // Generates Line and yield it
            $lineIToNext = new Line($linePoints[$i], $linePoints[$next]);
            yield $lineIToNext; 
            $i = $next; 
        } while ($i != 0); 
	}

    /**
     * Setter for one point of the LineString
     * @param int $idx Index in the array of the LineString to replace
     * @param Point $point Point to put in the specified position
     * @return LineString Current instance
     */
    public function setPoint(int $idx, Point $point): LineString {
        $this->points[$idx] = $point;
        return $this;
    }

    /**
     * Checks whether the LineString intersects a point. A Polygon intersects a Point if
     * at least one of its lines intersects the Point
     * @param Point $point Point to check
     * @return bool True if the LineString intersects with the point, false otherwise
     */
    public function intersectsPoint(Point $point): bool {
        $lines = $this->getLines();
        foreach ($lines as $line) {
            if ($line->intersectsPoint($point)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether the LineString intersects a line
     * @param Line $otherLine Line to check
     * @return bool True if the LineString intersects with the line, false otherwise
     */
    public function intersectsLine(Line $otherLine): bool {
        $lines = $this->getLines();
        foreach ($lines as $line) {
            if ($line->intersectsLine($otherLine)) {
                return true;
            } 
        }

        return false;
    }

    /**
     * Checks whether the LineString intersects another polygon
     * @param Polygon $polygon Polygon to check
     * @return bool True if the LineString intersects with the polygon, false otherwise
     */
    public function intersectsPolygon(Polygon $polygon): bool {
        $lines = $this->getLines();
        foreach ($lines as $line) {
            if ($polygon->intersectsLine($line)) {
                return true;
            } 
        }

        return false;
	}
	
	/**
     * Checks whether the LineString intersects another LineString
     * @param LineString $otherLineString LineString to check
     * @return bool True if the LineString intersects with the LineString, false otherwise
     */
    public function intersectsLineString(LineString $otherLineString): bool {
        $lines = $this->getLines();
        foreach ($lines as $line) {
            if ($otherLineString->intersectsLine($line)) {
                return true;
            } 
        }

        return false;
    }
}