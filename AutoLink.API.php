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

class AutoLink {
    public $id;
    public $project_id;
    public $regexp;
    public $replace;

    public function __construct($regexp, $replace, $project_id=null) {
        $this->project_id = $project_id;
        $this->regexp = $regexp;
        $this->replace = $replace;
    }

    public function save() {
        $table = plugin_table("links");

        if ($this->id === null) { /* CREATE */
            $query = "INSERT INTO {$table}
                               ( project_id, regex, repl )
                        VALUES ( ".db_param().", ".db_param().", ".db_param()." )";
            db_query($query, array(
                $this->project_id, $this->regexp, $this->replace));
            $this->id = db_insert_id($table);   
        }
        else {  /* UPDATE */
            $query = "UPDATE {$table} SET
                        project_id=".db_param().",
                        regex=".db_param().",
                        repl=".db_param()."
                        WHERE id=".db_param();
            db_query($query, array(
                $this->project_id, $this->regexp, $this->replace, $this->id));
        }
    }

    public function process($p_string)
    {
        return preg_replace($this->regexp, $this->replace, $p_string);
    }

    public static function load_by_id($id) {
        $t_table = plugin_table('links');

        if (is_array($id)) {
            $ids = array_filter($id, "is_int");
            if (count($ids) < 1)
                return array();
            $ids = implode(",", $ids);
            $query = "SELECT * FROM {$t_table} WHERE id IN ({$ids})";
            $result = db_query($query);
            return self::from_db_result($result);
        }
        else {
            $query = "SELECT * FROM {$t_table} WHERE id=".db_param();
            $result = db_query($query, array($id));
            $rules = self::from_db_result($result);
            return $rules[0];
        }
    }

    public static function load_for_project($p_proj) {
        $t_table = plugin_table('links');
        $t_query = "SELECT * FROM {$t_table}
                           WHERE project_id=".db_param();
        $t_result = db_query($t_query, array($p_proj));
        return self::from_db_result($t_result);
    }

    public static function load_all() {
        $t_table = plugin_table('links');
        $t_query = "SELECT * FROM {$t_table}";
        $t_result = db_query($t_query);
        return self::from_db_result($t_result);
    }

    public static function delete_by_id($id) {
        $t_table = plugin_table('links');
        if (is_array($id)) {
            $ids = array_filter($id, "is_int");
            if (count($ids) < 1) {
                return;
            }
            $ids = implode(",", $ids);
            $query = "DELETE FROM {$t_table} WHERE id IN ({$ids})";
            db_query($query);
        }
        else {
            $query = "DELETE FROM {$t_table} WHERE id=".db_param();
            db_query($query, array($id));
        }
    }

    public static function delete_by_project_id($p_project) {
        $t_table = plugin_table('links');
        $query = "DELETE FROM {$t_table} WHERE project_id=".db_param();
        db_query($query, $p_project);
    }

    /**
     * Create a copy of the given object with strings cleaned for output.
     *
     * @param object AutoLink object
     * @param string Target format
     * @return object Cleaned snippet object
     */
	public static function clean($dirty, $target="view") {
		if (is_array($dirty)) {
			$cleaned = array();
			foreach ($dirty as $id => $rule) {
				$cleaned[$id] = self::clean($rule, $target);
			}            
		} else {
			if ($target == "view") {
				$dirty->regexp = string_display_line($dirty->regexp);
				$dirty->replace = string_display($dirty->replace);
			} elseif ($target == "form") {
				$dirty->regexp = string_attribute($dirty->regexp);
				$dirty->replace = string_textarea($dirty->replace);
			}

			$cleaned = new AutoLink(
				$dirty->regexp,
				$dirty->replace,
				$dirty->project_id
			);
			$cleaned->id = $dirty->id;
		}

		return $cleaned;
	}

    private static function from_db_result($p_result) {
        $rules = array();
        if (!empty($p_result)) {
            while ($row = db_fetch_array($p_result)) {
                $rule = new AutoLink($row["regex"], $row["repl"], $row["project_id"]);
                $rule->id = $row["id"];
                $rules[$rule->id] = $rule;
            }
        }
        return $rules;
    }
}
