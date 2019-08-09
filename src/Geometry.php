<?php

namespace JWare\GeoPHP;

interface Geometry {
    /**
     * Gets the geometry area
     * @return float|double The geometry's area
     */
    public function area();
}