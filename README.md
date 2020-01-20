# GeoPHP

[GeoPHP][documentation_site] is a library of geospatial functions based on GeoRust and Turf.js.

## Installation

Install the library running:

`composer require jware/geophp`

## Usage

```
use \JWare\GeoPHP\Polygon;
use \JWare\GeoPHP\Point;

$polygon = new Polygon([
    new Point(1, -1),
    new Point(2, 1),
    new Point(3, -1),
    new Point(2, -2),
    new Point(1, -1)
]);

$point = new Point(-1, 2);
$point2 = new Point(2, 2);

$polygon->containsPoint($point); // False
$polygon->containsPoint($point2); // True
```

Please read the [documentation][documentation_site] to see the full method list available.

## Why another Geospatial library?

GeoPHP was born due to the lack of a modern geospatial library for PHP. The tools available today are not published in the Composer repositories and are not easy to use (some requires DB drivers or constructs the Geometries from complex Strings). GeoPHP offers:

- 🚀 Modern and fast: made in PHP 7 with fast algorithms
- 🥳 Friendly and simple interface: implemented with the Object Oriented Paradigm, allows its easy and fast use by the developer.
- 👨🏼‍💻 Easy access: the library is published in Composer for quick import into your projects.
- 🛠 Tested: all methods are covered by unit tests.
- 🌟 A lot of examples!

## Contributing

First of all, thanks for consider contributing to the project! Please, read [CONTRIBUTING.md](/CONTRIBUTING.md) for more.

<!-- TODO: change for real documentation UI URL -->
[documentation_site]: https://any.com "Full documentation"