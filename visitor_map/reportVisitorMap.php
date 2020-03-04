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
 * Class owa_reportVisitorMapController
 *
 * @category   Module
 * @author     Lauser, Nicolai (WinHelp GmbH) <n.lauser@winhelp.eu>
 * @copyright  2019 WinHelp GmbH
 * @version    $Id: f4e716bd5d2b87ecc31a269b13e7ff796056200f $
 */
class owa_reportVisitorMapController extends owa_reportController {

    /**
     *
     */
    public function action() {

        $this->setSubview('visitor_map.reportVisitorMap');
        $this->setTitle('Visitor Map');

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

        $db = owa_coreAPI::dbSingleton();

        $db->selectFrom($l->getTableName(), 'location');

        $db->join(OWA_SQL_JOIN, $s->getTableName(), 'session', 'location.id', 'session.location_id');

        $db->selectColumn('count(session.visitor_id) as visitor_count');
        $db->selectColumn('location.latitude as latitude');
        $db->selectColumn('location.longitude as longitude');

        $db->where('session.site_id', $this->getSiteIdParameterValue());
        $db->where('location.latitude', '(not set)', '!=');
        $db->where('location.longitude', '(not set)', '!=');

        if ($startDate && $endDate) {
            $db->where('session.yyyymmdd', array('start' => $startDate, 'end' => $endDate), 'BETWEEN');
        }

        $db->groupBy('session.visitor_id');
        $db->groupBy('location.latitude');
        $db->groupBy('location.longitude');

        $this->set('locations', json_encode($db->getAllRows()));
    }
}

require_once(OWA_BASE_DIR.'/owa_view.php');

/**
 * Class owa_reportVisitorMapView
 *
 * @category   Module
 * @author     Lauser, Nicolai (WinHelp GmbH) <n.lauser@winhelp.eu>
 * @copyright  2019 WinHelp GmbH
 * @version    $Id: f4e716bd5d2b87ecc31a269b13e7ff796056200f $
 */
class owa_reportVisitorMapView extends owa_view {

    /**
     *
     */
    public function render() {
        $this->body->setTemplateFile('visitor_map', 'report_visitor_map.tpl');
        $this->body->set('locations', $this->get('locations'));
    }
}