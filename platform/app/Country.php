<?php
namespace App;

use IPHP\Model\Model;

class Country extends Model {
    protected $table = 'countries';
    protected $primaryKeys = ['id'];
}