<?php

namespace app\components;


class URI {

    public function __construct() {

    }

    public function get() {
        return $_SERVER['REQUEST_URI'];
    }

    public function getSegmentsAsArray($segmentFrom = 0, $segmentsCount = null) {
        $allSegments = array_filter(explode('/', $this->get()));

        return array_slice($allSegments, $segmentFrom, $segmentsCount);
    }

    public function getSegmentsAfterRoute($routePath) {
        if (!$routePath) {
            return [];
        }

        $fromSegment = count(array_filter(explode('/', $routePath)));
        return $this->getSegmentsAsArray($fromSegment);
    }
}