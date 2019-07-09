<?php

namespace Genarito\GeoPHP;

interface Geometry {
    /**
     * Checks whether the line intersects with the specified geometry
     * @param Geometry $otherGeometry The other geometry to check whether intersect with the line or not
     * @return bool True if the line intersects with the specified geometry, false otherwise
     */
    public function intersects(Geometry $otherGeometry);
}