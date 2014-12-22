<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Library of functions for the booktool_minivalidator module.
 *
 * @package    booktool_minivalidator
 * @copyright  2014 Ivana Skelic, Hrvoje Golcic 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Adds module specific settings to the settings block
 *
 * @param settings_navigation $settings The settings navigation object
 * @param navigation_node $node The node to add module settings to
 */
 function booktool_minivalidator_extend_settings_navigation(settings_navigation $settings, navigation_node $node) {
 	global $PAGE, $DB;

 	$params = $PAGE->url->params();
 	if (empty($params['id'])) {
 		return;
 	}

 	$cm = get_coursemodule_from_id('book', $params['id'], 0, false, MUST_EXIST);
	$course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
	$book = $DB->get_record('book', array('id'=>$cm->instance), '*', MUST_EXIST);


	if (has_capability('booktool/minivalidator:valid', $PAGE->cm->context)) {
 		
 		$url_img = new moodle_url(
 			'/mod/book/tool/minivalidator/images.php', 
 			array('id'=>$params['id'])
 		);
 		$node->add(
 			get_string('check_img', 'booktool_minivalidator'), 
 			$url_img, 
 			navigation_node::TYPE_SETTING, 
 			null, 
 			null, 
 			new pix_icon('image', '', 'booktool_minivalidator', array('class'=>'icon'))
 		);
 		
 		$url_table = new moodle_url(
 			'/mod/book/tool/minivalidator/tables.php', 
 			array('id'=>$params['id'])
 		);
 		$node->add(
 			get_string('check_table', 'booktool_minivalidator'), 
 			$url_table, 
 			navigation_node::TYPE_SETTING, 
 			null, 
 			null, 
 			new pix_icon('table', '', 'booktool_minivalidator', array('class'=>'icon'))
 		);
	}
 		
}