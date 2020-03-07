<?php
/**
 * This file is part of the OpenWebAnalytics - VisitorMap module.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    http://www.gnu.org/copyleft/gpl.html GPL v2.0
 */

require_once(OWA_BASE_DIR.'/owa_reportController.php');

/**
 * Class owa_reportConversionMapController
 *
 * @category   Module
 * @author     Lauser, Nicolai (WinHelp GmbH) <n.lauser@winhelp.eu>
 * @copyright  2019 WinHelp GmbH
 * @version    $Id: f4e716bd5d2b87ecc31a269b13e7ff796056200f $
 */
class owa_reportConversionMapController extends owa_reportController {

    /**
     *
     */
    public function action() {

        $this->setSubview('visitor_map.reportConversionMap');
        $this->setTitle('Conversion Map');

        $startDate = $this->getParam('startDate');
        $endDate = $this->getParam('endDate');

        /**
         * @var owa_entity $l
         */
        $l = owa_coreAPI::entityFactory('base.location_dim');

        /**
         * @var owa_entity $s
         */
        $s = owa_coreAPI::entityFactory('base.session');

        /**
         * @var owa_entity $c
         */
        $c = owa_coreAPI::entityFactory('base.commerce_transaction_fact');

        $db = owa_coreAPI::dbSingleton();

        $db->selectFrom($l->getTableName(), 'location');

        $db->join(OWA_SQL_JOIN, $s->getTableName(), 'session', 'location.id', 'session.location_id');
        $db->join(OWA_SQL_JOIN, $c->getTableName(), 'conversion', 'session.id', 'conversion.session_id');

        $db->selectColumn('sum(conversion.total_revenue) as total_revenue');
        $db->selectColumn('location.latitude as latitude');
        $db->selectColumn('location.longitude as longitude');

        $db->where('conversion.site_id', $this->getSiteIdParameterValue());
        $db->where('location.latitude', '(not set)', '!=');
        $db->where('location.longitude', '(not set)', '!=');

        if ($startDate && $endDate) {
            $db->where('conversion.yyyymmdd', array('start' => $startDate, 'end' => $endDate), 'BETWEEN');
        }

        $db->groupBy('conversion.total_revenue');
        $db->groupBy('location.latitude');
        $db->groupBy('location.longitude');

        $conversions = $db->getAllRows();

        if (!$conversions) {
            $conversions = [];
        }

        $this->set('locations', json_encode($conversions));
    }
}

require_once(OWA_BASE_DIR.'/owa_view.php');

/**
 * Class owa_reportConversionMapView
 *
 * @category   Module
 * @author     Lauser, Nicolai (WinHelp GmbH) <n.lauser@winhelp.eu>
 * @copyright  2019 WinHelp GmbH
 * @version    $Id: f4e716bd5d2b87ecc31a269b13e7ff796056200f $
 */
class owa_reportConversionMapView extends owa_view {

    /**
     *
     */
    public function render() {
        $this->body->setTemplateFile('visitor_map', 'report_conversion_map.tpl');
        $this->body->set('locations', $this->get('locations'));
    }
}