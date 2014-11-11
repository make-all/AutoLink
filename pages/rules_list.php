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

access_ensure_global_level(plugin_config_get("edit_threshold"));

$admin = access_has_global_level(config_get('manage_plugin_threshold'));

html_page_top();
print_manage_menu();
$rules = AutoLink::load_all();

?>
<br />
<form action="<?php echo plugin_page('rule_list_action')?>" method="post">
  <?php echo form_security_field("plugin_AutoLink_rule_list_action") ?>
  <table class="width75" align="center">
    <tr>
      <td class="form_title" colspan="3"><?php echo plugin_lang_get('rules_list_title') ?></td>
      <td class="right">
	<?php
	   if ($admin) {
	     print_bracket_link(plugin_page("config"), plugin_lang_get('config'));
	   }
	 ?></td>
    </tr>

    <tr class="row-category">
      <td width="5%"> </td>
      <td><?php echo plugin_lang_get('rule_project') ?></td>
      <td><?php echo plugin_lang_get('rule_regexp') ?></td>
      <td><?php echo plugin_lang_get('rule_replace') ?></td>
    </tr>
    <?php foreach($rules as $rule): ?>
      <tr <?php echo helper_alternate_class() ?>>
	<td class="center"><input type="checkbox" name="rule_list[]" value="<?php echo $rule->id ?>"/></td>
	<td><?php echo project_get_name($rule->project_id) ?></td>
	<td><?php echo string_html_specialchars($rule->regexp) ?></td>
	<td><?php echo string_html_specialchars($rule->replace) ?></td>
      </tr>
    <?php endforeach ?>
    <tr>
      <td class="center"><input class="rules_select_all" type="checkbox"/></td>
      <td colspan="3">
	<select class="rules_select_action" name="action">
	  <option value="edit"><?php echo plugin_lang_get("action_edit") ?></option>
	  <option value="delete"><?php echo plugin_lang_get("action_delete") ?></option>
	</select>
	<input class="rules_select_submit" type="submit" value="<?php echo plugin_lang_get('action_go') ?>">
      </td>
    </tr>
  </table>
</form>

<br />
<form action="<?php echo plugin_page('rule_create') ?>" method="post">
  <?php echo form_security_field("plugin_AutoLink_rule_create") ?>
  <table class="width75" align="center">
    <tr>
      <td class="form-title" colspan="2"><?php echo plugin_lang_get("create_title") ?></td>
    </tr>
    <tr class="row-1">
      <td class="category"><?php echo plugin_lang_get("create_project")?></td>
    <td><select name="project"><?php print_project_option_list(null, true) ?></select></td>
    </tr>
    <tr class "row-2">
      <td class="category"><?php echo plugin_lang_get("create_regexp") ?></td>
      <td><input name="regexp" /></td>
    </tr>
    <tr class="row-1">
      <td class="category"><?php echo plugin_lang_get("create_replace") ?></td>
      <td><input name="replace" /></td>
    </tr>
    <tr>
      <td class="center" colspan="2"><input type="submit" value="<?php echo plugin_lang_get('action_create') ?>" /></td>
    </tr>
  </table>
</form>

<?php
       html_page_bottom();
