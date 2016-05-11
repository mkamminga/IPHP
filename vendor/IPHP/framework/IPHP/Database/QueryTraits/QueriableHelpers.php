<?php
namespace IPHP\Database\QueryTraits;

trait QueriableHelpers {
	protected function appendTo (string $andOr, string $query, array $params = [], string &$addToQuery, array &$addToParams = []) {
		if (!empty($addToQuery)){
			$addToQuery.= ' ' . (($andOr == 'AND' || $andOr == 'OR') ? $andOr : 'AND') . ' ';
		}

		$addToQuery.= $query;

		foreach ($params as $param) {
			$addToParams[] = $param;
		}
	}
}