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

form_security_validate("plugin_AutoLink_rule_list_action");
access_ensure_global_level(plugin_config_get("edit_threshold"));

$action = gpc_get_string("action");
$rule_list = gpc_get_int_array("rule_list", array());

if (count($rule_list) < 1) {
    form_security_purge("plugin_AutoLink_rule_list_action");
    print_header_redirect(plugin_page("rules_list", true));
}

$rules = AutoLink::load_by_id($rule_list);

function array_object_properties($arr, $prop) {
    $props = array();
    foreach ($arr as $key => $obj) {
	$props[$key] = $obj->$prop;
    }
    return $props;
}

/* DELETE */
if ($action == "delete") {
    $rule_regexps = array_object_properties(AutoLink::clean($rules));

    helper_ensure_confirmed(plugin_lang_get("action_delete_confirm") . "<br />" . implode(", ", $rule_regexps), plugin_lang_get("action_delete"));
    AutoLink::delete_by_id(array_keys($rules));
    form_security_purge("plugin_AutoLink_rule_list_action");
    print_successful_redirect(plugin_page("rules_list", true));
}
/* EDIT */
elseif ($action == "edit") {
    $rules = AutoLink::clean($rules, "form");
    html_page_top();
?>
<br />
<form action="<?php echo plugin_page('rule_list_action')?>" method="post">
  <?php echo form_security_field("plugin_AutoLink_rule_list_action") ?>
  <input type="hidden" name="action" value="update"/>
  <table class="width75" align="center">
    <tr>
      <td class="form_title" colspan="3"><?php echo plugin_lang_get("edit_title") ?></td>
    </tr>
    <?php $first = true; foreach($rules as $rule): ?>
    <?php if (!$first): ?>
    <tr class="spacer"><td></td></tr>
    <?php endif ?>
    <tr class="row-1">
      <td class="center" rowspan="3"><input type="checkbox" name="rule_list[]" value="<?php echo $rule->id ?>" checked="checked" /></td>
      <td class="category"><?php echo plugin_lang_get("rule_project") ?></td>
    <td><select name="project_<?php echo $rule->id ?>" value="<?php echo $rule->project_id ?>"><?php print_project_option_list($rule->project_id, true) ?></select></td>
    </tr>
    <tr class="row-2">
      <td class="category"><?php echo plugin_lang_get("rule_regexp") ?></td>
      <td><input name="regexp_<?php echo $rule->id ?>" value="<?php echo $rule->regexp ?>" /></td>
    </tr>
    <tr class="row-1">
      <td class="category"><?php echo plugin_lang_get("rule_replace") ?></td>
      <td><input name="replace_<?php echo $rule->id ?>" value="<?php echo $rule->replace ?>" /></td>
    </tr>
    <?php $first = false; endforeach ?>
    <tr>
      <td><input type="checkbox" class="rules_select_all" checked="checked" /></td>
      <td class="center" colspan="2"><input type="submit" value="<?php echo plugin_lang_get('action_edit') ?>" /></td>
    </tr>
  </table>
</form>
<?php
    html_page_bottom();
}
elseif ($action == "update") {
    foreach($rules as $rule_id => $rule) {
        $new_project = gpc_get_int("project_{$rule_id}");
        $new_regexp = gpc_get_string("regexp_{$rule_id}");
        $new_replace = gpc_get_string("replace_{$rule_id}");

        if ($rule->project_id != $new_project
            || $rule->regexp != $new_regexp
            || $rule->replace != $new_replace) {
            $rule->project_id = $new_project;
            if (!is_blank($new_regexp))
                $rule->regexp = $new_regexp;
            if (!is_blank($new_replace))
                $rule->replace = $new_replace;
            $rule->save();
        }
    }
    form_security_purge("plugin_AutoLink_rule_list_action");
    print_successful_redirect(plugin_page("rules_list", true));
}