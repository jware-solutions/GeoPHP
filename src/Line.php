<?php

namespace Genarito\GeoPHP;

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
     * Getter of the starting point of the line
     * @return Point starting point of the line
     */
    public function getStart() {
        return $this->start;
    }

    /**
     * Setter of the starting point of the line
     * @param Point $start starting point of the line
     * @return Line Instance
     */
    public function setStart(Point $start) {
        $this->start = $start;
        return $this;
    }

    /**
     * Getter of the ending point of the line
     * @return Point ending point of the line
     */
    public function getEnd() {
        return $this->end;
    }

    /**
     * Setter of the ending point of the line
     * @param Point $end ending point of the line
     * @return Line Instance
     */
    public function setEnd(Point $end) {
        $this->end = $end;
        return $this;
    }

    /**
     * Calculates the determinant of the line:
     * line.start.x * line.end.y - line.start.y * line.end.x
     * @return float|double The determinant of the line
     */
    public function determinant() {
        return $this->start->getX() * $this->end->getY() - $this->start->getY() * $this->end->getX();
    }

    /**
     * Calculates the difference in 'x' components (Δx):
     * line.end.x - line.start.x
     * @return float|double The difference in 'x' components (Δx)
     */
    public function dx() {
        return $this->end->getX() - $this->start->getX();
    }

    /**
     * Calculates the difference in 'y' components (Δy):
     * line.end.y - line.start.y
     * @return float|double The difference in 'y' components (Δy)
     */
    public function dy() {
        return $this->end->getY() - $this->start->getY();
    }

    /**
     * Calculates the slope of a line:
     * line.dy() / line.dx()
     * @return float|double The slope of a line
     */
    public function slope() {
        $dx = $this->dx();
        return ($dx != 0.) ? $this->dy() / $dx : INF;
    }
}