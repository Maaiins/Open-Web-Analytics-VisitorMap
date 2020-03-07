<?php
/**
 * This file is part of the OpenWebAnalytics - VisitorMap module.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    http://www.gnu.org/copyleft/gpl.html GPL v2.0
 */

require_once(OWA_BASE_DIR.'/owa_module.php');

/**
 * Class owa_visitor_mapModule
 *
 * @category   Module
 * @author     Lauser, Nicolai (WinHelp GmbH) <n.lauser@winhelp.eu>
 * @copyright  2019 WinHelp GmbH
 * @version    $Id: f4e716bd5d2b87ecc31a269b13e7ff796056200f $
 */
class owa_visitor_mapModule extends owa_module {

    /**
     * owa_visitor_mapModule constructor.
     */
	public function __construct() {
		$this->name = 'visitor_map';
		$this->display_name = 'Visitor Map';
		$this->group = 'visitor_map';
		$this->author = 'Nicolai Lauser';
		$this->version = '1.0';
		$this->description = 'Simple module to display visitors on map';
		$this->config_required = false;
		$this->required_schema_version = 1;
		
		return parent::__construct();
	}

    /**
     * @return bool|void
     */
	public function registerNavigation() {
        $this->addNavigationLinkInSubGroup( 'Visitors', 'visitor_map.reportVisitorMap', 'Map', 99);
        $this->addNavigationLinkInSubGroup( 'Ecommerce', 'visitor_map.reportConversionMap', 'Map', 99);
	}
}
?>