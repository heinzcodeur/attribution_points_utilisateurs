<?php


namespace App\Data;


use App\Entity\Groupe;

class SearchUser
{
    /**
     * @var int
     */
    public $page = 1;
    /**
     * @var string
     */
    public $name = '';

    /**
     * @var Groupe[]
     */
    public $groupe = [];

    /**
     * @var null|integer
     */
    public $max;

    /**
     * @var null|integer
     */
    public $min;
}