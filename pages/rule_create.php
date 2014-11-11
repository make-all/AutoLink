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

form_security_validate("plugin_AutoLink_rule_create");
access_ensure_global_level(plugin_config_get("edit_threshold"));

$project = gpc_get_int("project");
$regexp = gpc_get_string("regexp");
$replace = gpc_get_string("replace");

if (is_blank($regexp))
    plugin_error("regexp_empty");

if (is_blank($replace))
    plugin_error("replace_empty");

$rule = new AutoLink($regexp, $replace, $project);
$rule->save();

form_security_purge("plugin_AutoLink_rule_create");
print_successful_redirect(plugin_page("rules_list", true));
