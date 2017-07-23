<?php
# AutoLink plugin - a MantisBT plugin for linking text
#
# Copyright (C) 2014 The Maker - make-all@users.github.com
#
# AutoLink plugin is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.

require_once( config_get( 'class_path' ) . 'MantisFormattingPlugin.class.php');

class AutoLinkPlugin extends MantisFormattingPlugin {

    function register() {
        $this->name = plugin_lang_get('title');
        $this->description = plugin_lang_get('description');
        $this->page = 'config_page';

        $this->version = '1.1';
        $this->requires = array(
            'MantisCore' => '2.0.0',
        );
        $this->uses = array(
            'MantisCoreFormatting' => '2.0.0',
        );
        $this->author = 'The Maker';
        $this->contact = 'make-all@users.github.com';
        $this->url = 'https://.github.com/make-all/AutoLink';
    }

    function config() {
        return array(
            "edit_threshold" => ADMINISTRATOR,  
	    "process_text" => true,
	    "process_urls" => true,
	    "process_buglinks" => true,
	    "process_vcslinks" => true
        );
    }

    function schema() {
        return array(
            array('CreateTableSQL',
	       array(plugin_table('links'), "
                   id         I    NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
                   project_id I    UNSIGNED,
                   regex     C(64) NOTNULL,
                   repl    C(64) NOTNULL",
	       array('mysql' => 'DEFAULT CHARSET=utf8')
            )),
        );
    }

    function hooks() {
        return array(
            'EVENT_MENU_MANAGE' => 'menu_manage',
            'EVENT_DISPLAY_TEXT' => 'text',
            'EVENT_DISPLAY_FORMATTED' => 'formatted',
            'EVENT_DISPLAY_RSS' => 'rss',
            'EVENT_DISPLAY_EMAIL' => 'email',
        );
    }

    function init() {
        require_once('AutoLink.API.php');
    }

    /**
     * Per project custom autolink text.
     * @param project whose rules to process (or null for global rules)
     * @param original text
     * @return formatted text
     */
    function link_project_text($p_project, $p_string) {
        $t_string = $p_string;

        $links = AutoLink::load_for_project($p_project);

        foreach($links as $rule) {
            $t_string = $rule->process($t_string);
        }

        return $t_string;
    }

    /**
     * Processing of custom autolink text.
     * @param original string
     * @return formatted text
     */
    function autolink_custom_text($p_string) {
        $t_string = $p_string;
        /* works properly only when user is logged in  */
        if ( auth_is_user_authenticated() )
			$project = helper_get_current_project();
        else
            $project = ALL_PROJECTS;

        $parents = project_hierarchy_inheritance( $project );

        if ($project != null) {
            /* Project specific links.  */
            $t_string = $this->link_project_text($project, $t_string);
            foreach ($parents as $prj) {
                if ($prj != $project && $prj != null) {
                    $t_string = $this->link_project_text($prj, $t_string);
                }
            }
        }
        /* General links */
        $t_string = $this->link_project_text(ALL_PROJECTS, $t_string);
        return $t_string;
    }

    /**
     * Overall processing
     * @param original string
     * @param multiline flag
     * @return formatted text
     */
    function autolink_text($p_string, $p_multiline) {
        $t_string = $p_string;
        if (ON == plugin_config_get('process_text')) {
            $t_string = string_strip_hrefs($t_string);
            $t_string = string_html_specialchars($t_string);
            $t_string = string_restore_valid_html_tags($t_string, true);
            if ($p_multiline) {
                $t_string = string_preserve_spaces_at_bol($t_string);
                $t_string = string_nl2br($t_string);
            }
        }
        if (ON == plugin_config_get('process_urls')) {
            $t_string = string_insert_hrefs($t_string);
        }
        if (ON == plugin_config_get('process_buglinks')) {
            $t_string = string_process_bug_link($t_string);
            $t_string = string_process_bugnote_link($t_string);
        }
        if (ON == plugin_config_get('process_vcslinks')) {
            $t_string = string_process_cvs_link($t_string);
        }
        $t_string = $this->autolink_custom_text($t_string);

        return $t_string;
    }

    /**
     * Plain text processing
     * @param string Event name
     * @param string Original text
     * @param boolean not used, satisfying superclass interface.
     * @return string the formatted text
     */
    function text( $p_event, $p_string, $p_multiline = true) {
        $t_string = $p_string;
        $t_string = $this->autolink_text($t_string, $p_multiline);
        return $t_string;
    }

    function formatted( $p_event, $p_string, $p_multiline = true) {
        $t_string = $p_string;
        $t_string = $this->autolink_text($t_string, $p_multiline);
        return $t_string;
    }

    function rss($p_event, $p_string) {
        $t_string = $p_string;
        $t_string = $this->autolink_text($t_string, false);
	return $t_string;
    }

    function email($p_event, $p_string) {
        $t_string = $p_string;
        $t_string = $this->autolink_text($t_string, false);
	return $t_string;
    }

    function menu_manage() {
        $page = plugin_page("rules_list");
        $label = plugin_lang_get("rules_list_title");
        return '<a href="'.string_html_specialchars($page).'">'.$label.'</a>';
    }
}
