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

   access_ensure_global_level(config_get("manage_plugin_threshold"));

   html_page_top();
   print_manage_menu();
?>
<br />
<form action="<?php echo plugin_page('config') ?>" method="post">
<?php echo form_security_field("plugin_AutoLink_config") ?>
<table class="width60" align="center">
  <tr>
    <td class="form-title" colspan="2"><?php echo plugin_lang_get("config_title") ?></td>
  </tr>
  <tr <?php echo helper_alternate_class() ?>>
    <td class="category"><?php echo plugin_lang_get('edit_threshold') ?></td>
    <td><select name="edit_threshold"><?php print_enum_string_option_list('access_levels', plugin_config_get('edit_threshold')) ?></select></td>
  </tr>
  <tr <?php echo helper_alternate_class() ?>>
    <td class="category"><?php echo plugin_lang_get('process_text') ?></td>
    <td><input type="checkbox" name="process_text" <?php echo ( plugin_config_get('process_text') ? 'checked="checked" ' : '') ?>/></td>
  </tr>
  <tr <?php echo helper_alternate_class() ?>>
    <td class="category"><?php echo plugin_lang_get('process_urls') ?></td>
    <td><input type="checkbox" name="process_urls" <?php echo ( plugin_config_get('process_urls') ? 'checked="checked" ' : '') ?>/></td>
  </tr>
  <tr <?php echo helper_alternate_class() ?>>
    <td class="category"><?php echo plugin_lang_get('process_buglinks') ?></td>
    <td><input type="checkbox" name="process_buglinks" <?php echo ( plugin_config_get('process_buglinks') ? 'checked="checked" ' : '') ?>/></td>
  </tr>
  <tr <?php echo helper_alternate_class() ?>>
    <td class="category"><?php echo plugin_lang_get('process_vcslinks') ?></td>
    <td><input type="checkbox" name="process_vcslinks" <?php echo ( plugin_config_get('process_vcslinks') ? 'checked="checked" ' : '') ?>/></td>
  </tr>
  <tr>
    <td class="center" colspan="2"><input type="submit" value="<?php echo plugin_lang_get('action_update') ?>" /></td>
  </tr>
</table>
</form>

<?php
   html_page_bottom();

