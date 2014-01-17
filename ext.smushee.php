<?php
/**
 * Smushee for ExpressionEngine
 *
 * Takes any image uploaded via ExpressionEngine
 * and processes it via Yahoo!'s Smush.it API
 *
 * @author Andie Fairlie <andief.com>
 * @copyright 2012
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @package smushee
 * @version 1.0 - 30-12-2012
 */
 
class Smushee_ext {

    var $name       = 'Smushee';
    var $version        = '1.0';
    var $description    = 'Compresses all images uploaded through Smushee';
    var $settings_exist = 'n';
    var $docs_url       = ''; // 'http://expressionengine.com/user_guide/';

    var $settings       = array();

    /**
     * Constructor
     *
     * @param   mixed   Settings array or empty string if none exist.
     */
    function __construct($settings = '')
    {
        $this->EE =& get_instance();

        $this->settings = $settings;
    }
		
		function activate_extension()
		{
				$this->settings = array();
		
				$data = array(
						'class'     => __CLASS__,
						'method'    => 'smush',
						'hook'      => 'file_after_save',
						'settings'  => serialize($this->settings),
						'priority'  => 10,
						'version'   => $this->version,
						'enabled'   => 'y'
				);
		
				$this->EE->db->insert('extensions', $data);
		}		
		
		function update_extension($current = '')
		{
				if ($current == '' OR $current == $this->version)
				{
						return FALSE;
				}
		
				if ($current < '1.0')
				{
						// Update to version 1.0
				}
		
				$this->EE->db->where('class', __CLASS__);
				$this->EE->db->update(
										'extensions',
										array('version' => $this->version)
				);
		}
				
		function disable_extension()
		{
				$this->EE->db->where('class', __CLASS__);
				$this->EE->db->delete('extensions');
		}
		
		
		function smush($file_id,$data)
		{
			$type = $data['mime_type'];
			
			
			// Only smush images
			if (strpos($type,'image') !== false) {
				require_once('smush.php');
				$file = $data['rel_path'];
				$smushed = smush_file($file);
			}
		}
		
		
}
// END CLASS
?>