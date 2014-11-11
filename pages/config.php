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

form_security_validate("plugin_AutoLink_config");
access_ensure_global_level(config_get("manage_plugin_threshold"));

/* Avoid touching timestamp if no change.  */
function maybe_set_option($name, $value) {
    if ($value != plugin_config_get($name)) {
        plugin_config_set($name, $value);
    }
}

maybe_set_option("edit_threshold", gpc_get_int("edit_threshold"));
maybe_set_option("process_text", gpc_get_bool("process_text", OFF));
maybe_set_option("process_urls", gpc_get_bool("process_urls", OFF));
maybe_set_option("process_buglinks", gpc_get_bool("process_buglinks", OFF));
maybe_set_option("process_vcslinks", gpc_get_bool("process_vcslinks", OFF));

form_security_purge("plugin_AutoLink_config");
print_successful_redirect(plugin_page("config_page", true));
