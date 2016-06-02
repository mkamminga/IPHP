<?php
namespace App;

use IPHP\Model\Model;
use IPHP\Database\Where;

class VatRate extends Model {
	protected $table = 'vat_rates';
	protected $primaryKeys = ['id'];
}