<?php

namespace JWare\GeoPHP;

interface Geometry {
    /**
     * Gets the geometry area
     * @return float|double The geometry's area
     */
    public function area();

    /**
     * Generate a clone instance
     * @return any New iinstance with the same parameters as original instance
     */
    public function clone();
}