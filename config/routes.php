<?php

declare (strict_types=1);

use \Hood\Arrow\Target as Target;

Target::register('get', 'teste/url', function(){
	echo "teste";
});

Target::register('get', 'teste/bla', function(){
	echo "teste";
});
