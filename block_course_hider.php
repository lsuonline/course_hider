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
 * Course Hider Tool
 *
 * @package   block_course_hider
 * @copyright 2008 onwards Louisiana State University
 * @copyright 2008 onwards David Lowe
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Block Base
// class block_course_hider extends block_base {

// Or Block List
class block_course_hider extends block_list {

    public $listview;

    function init() {
        $this->title = get_string('pluginname', 'block_course_hider');
    }

    /**
     * Indicates that this block has its own configuration settings
     * @return @bool
     */
    public function has_config() {
        return true;
    }

    function get_content() {
        global $CFG, $OUTPUT;

        $this->listview = true;

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }


        $this->content = $this->get_new_content_container();

        // Do we want to show the list view or the block view?
        if ($this->listview == true) {
            $this->populate_list_view();
        } else {
            // Show the block view
        }

        // user/index.php expect course context, so get one if page has module context.
        $currentcontext = $this->page->context->get_course_context(false);

        if (! empty($this->config->text)) {
            $this->content->text = $this->config->text;
        }

        $this->content = '';
        if (empty($currentcontext)) {
            return $this->content;
        }
        if ($this->page->course->id == SITEID) {
            $this->content->text .= "site context";
        }

        if (! empty($this->config->text)) {
            $this->content->text .= $this->config->text;
        }

        return $this->content;
    }

    private function populate_list_view() {

        $this->add_item_to_content([
            'lang_key' => get_string('sample_view', 'block_course_hider'),
            'icon_key' => 'i/mnethost',
            'page' => '/blocks/course_hider/course_hider.php'
        ]);

        $this->add_item_to_content([
            'lang_key' => get_string('simple_create', 'block_course_hider'),
            'icon_key' => 'i/mnethost',
            'page' => '/blocks/course_hider/course_hider.php',
            'query_string' => ['vform' => 1]
        ]);
    }
    // my moodle can only have SITEID and it's redundant here, so take it away
    public function applicable_formats() {
        return array(
            'all' => false,
            'site' => true,
            'site-index' => true,
            'course-view' => true, 
            'course-view-social' => false,
            'mod' => true, 
            'mod-quiz' => false
        );
    }

    /**
     * Builds and adds an item to the content container for the given params
     *
     * @param  array $params  [lang_key, icon_key, page, query_string]
     * @return void
     */
    private function add_item_to_content($params) {
        if (!array_key_exists('query_string', $params)) {
            $params['query_string'] = [];
        }

        $item = $this->build_item($params);

        $this->content->items[] = $item;
    }


    public function instance_allow_multiple() {
          return true;
    }

    /**
     * [cron description]
     * @return [type] [description]
     */
    public function cron() {
        return true;
    }

    /**
     * Returns an empty "block list" content container to be filled with content.
     *
     * @return @object
     */
    private function get_new_content_container() {
        $content = new stdClass;
        $content->items = array();
        $content->icons = array();
        $content->footer = '';

        return $content;
    }
}
