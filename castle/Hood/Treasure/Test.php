<?php

namespace Hood\Treasure;

class Test{
    public function teste() {
        foreach (range(1,10) as $id) {
            yield $id;
        }
    }
}
