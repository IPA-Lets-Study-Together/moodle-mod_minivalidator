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
 * booktool_minivalidator module local lib functions
 *
 * @package    booktool_minivalidator
 * @copyright  2014 Ivana Skelic, Hrvoje Golcic 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->libdir.'/filelib.php');

/**
 * Finds and shows all images and their alt attribute
 *
 * @param  	stdClass $book->id
 * @param 	$chapterid
 * @return 	
 */
function find_images($bookid, $chapterid, $contextid, $id) {
	
	global $DB, $CFG;

	$query = $DB->get_field('book_chapters', 'content', array('id'=>$chapterid, 'bookid'=>$bookid));
	$query = file_rewrite_pluginfile_urls($query, 'pluginfile.php', $contextid, 'mod_book', 'chapter', $chapterid);
	$content = serialize($query);

	$img_pat = '/<img[^>]+>/i'; //regular expression for image tag search
	preg_match_all($img_pat, $query, $img_pregmatch);

	if (!empty($img_pregmatch[0])) {

		foreach($img_pregmatch[0] as $image) {

			$alt_pat = '/(alt)=("[^"]*")/i';

			preg_match_all($alt_pat, $image, $alt_pregmatch);

			if (empty($alt_pregmatch[0])) {
				echo '<div><p><b>' . get_string('image', 'booktool_minivalidator') . '</b></p>';
			} else {
				echo "<div> <p>This image has description.</p>";
			}

			echo "<div>". $image . "</div></div><br><br>"; 

			if (empty($alt_pregmatch[0])) {
				echo '<a href="'.$CFG->wwwroot . '/mod/book/edit.php?id='.$id.'&chapter='.$chapterid.'" >Validate </a>';
			}//echoes image

			foreach ($alt_pregmatch[2] as $alt) {

				echo '<div style="margin-top:10px;">'.$alt; //echoes alt
				echo '<br>' . '<b>' . get_string('words','booktool_minivalidator') . ': </b>' . str_word_count($alt) .'<br>';
		
			}
		}		
	} else {
		echo get_string('no_images','booktool_minivalidator');
	}
}

/**
 * Finds and prints all tables and their summary attribute
 *
 * @param  	stdClass $book->id
 * @param 	$chapterid
 * @return 	
 */
function find_tables($bookid, $chapterid) {
	global $DB;

	$query = $DB->get_field('book_chapters', 'content', array('id'=>$chapterid, 'bookid'=>$bookid));
	//$content = serialize($query);

	$table_pat = '/<table(.*?)>.*?<\/table>/s'; //regular expression for table tag search
	preg_match_all($table_pat, $query, $table_pregmatch);

	if (!empty($table_pregmatch[0])) {

		foreach($table_pregmatch[0] as $table) {

			echo $table . "<br>"; //echoes table

			$summ_pat = '/(summary)=("[^"]*")/i';
			preg_match_all($summ_pat, $table, $summ_pregmatch);

			if (empty($summ_pregmatch[0])) {
				echo '<b>' . get_string('table', 'booktool_minivalidator') . '</b>';
			}

			foreach ($summ_pregmatch[2] as $summary) {
				echo $summary; //echoes summary
				echo '<br>' . '<b>' . get_string('words','booktool_minivalidator') . ': </b>' . str_word_count($summary) .'<br>'; 
			}
		}		
	} else {
		echo get_string('no_tables','booktool_minivalidator');
	}
}