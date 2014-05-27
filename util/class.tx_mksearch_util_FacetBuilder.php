<?php
/**
 * 	@package tx_mksearch
 *  @subpackage tx_mksearch_util
 *  @author Hannes Bochmann
 *
 *  Copyright notice
 *
 *  (c) 2011 das MedienKombinat GmbH <kontakt@das-medienkombinat.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 */

/**
 * benötigte Klassen einbinden
 */
require_once(t3lib_extMgm::extPath('rn_base') . 'class.tx_rnbase.php');

/**
 * Der FacetBuilder erstellt aus den Rohdaten
 * der Facets passende Objekte für das Rendering.
 *
 * @package tx_mksearch
 * @subpackage tx_mksearch_util
 * @author Hannes Bochmann <hannes.bochmann@das-medienkombinat.de>
 * @author Michael Wagner <michael.wagner@das-medienkombinat.de>
 */
class tx_mksearch_util_FacetBuilder {

	/**
	 * get singelton
	 *
	 * @param string $class
	 * @return tx_mksearch_util_FacetBuilder
	 */
	public static function getInstance($class = '') {
		static $instance;
		$class = empty($class) ? 'tx_mksearch_util_FacetBuilder' : $class;
		if (!$instance[$class]) {
			$instance[$class] = tx_rnbase::makeInstance($class);
		}
		return $instance[$class];
	}

	/**
	 * Baut die Daten für die Facets zusammen
	 *
	 * @param array|stdClass $facetData Daten von Solr
	 * @return array Ausgabedaten
	 */
	public function buildFacets($facetData) {
		$facetGroups = array();
		if (!$facetData) {
			return $facetGroups;
		}
		$uid = 0;
		foreach ($facetData As $field => $facetGroup) {
			if (empty($facetGroups[$field])) {
				$facetGroups[$field] = tx_rnbase::makeInstance(
					'tx_rnbase_model_base',
					array(
						'uid' => ++$uid,
						'field' => $field,
						'items' => array(),
					)
				);
			}
			foreach ($facetGroup As $id => $count) {
				$facetGroups[$field]->record['items'][] = $this->getSimpleFacet($field, $id, $count);
			}
		}
		return array_values($facetGroups);
	}

	protected function buildGroupedFacet() {

	}

	/**
	 * Liefert eine simple Facette zurück
	 *
	 * @param string $field
	 * @param int $id
	 * @param int $count
	 * @return tx_mksearch_model_Facet
	 */
	private function getSimpleFacet($field, $id, $count) {
		$title = $id;
		return tx_rnbase::makeInstance('tx_mksearch_model_Facet', $field, $id, $title, $count);
	}
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mksearch/util/class.tx_mksearch_util_FacetBuilder.php']) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mksearch/util/class.tx_mksearch_util_FacetBuilder.php']);
}