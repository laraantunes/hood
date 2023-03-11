<?php
/**
 * 2019 Hood Framework
 */

namespace Models;

use Hood\Treasure\Model;

class Book extends Model
{
    public static $table = 'book';
    public static $fields = [
        'id' => 'id',
        'title' => 'title',
        'rating' => 'rating',
        'price' => 'price',
    ];

    public $id;
    public $title;
    public $rating;
    public $price;
}