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

auth_reauthenticate();
access_ensure_global_level(plugin_config_get("edit_threshold"));

$admin = access_has_global_level(config_get('manage_plugin_threshold'));

layout_page_header( plugin_lang_get('title') );
layout_page_begin();
print_manage_menu();
$rules = AutoLink::load_all();

?>

<div class="col-md-12 col-xs-12">
	<div class="space-10"></div>
	<div class="form-container">
		<form action="<?php echo plugin_page('rule_list_action')?>" method="post" class="form-inline">
			<div class="widget-box widget-color-blue2">
				<div class="widget-header"><span class="col-md-10 col-xs-8"><h4 class="widget-title lighter"><?php echo plugin_lang_get('rules_list_title') ?></h4></span><span class="col-md-2 col-xs-4">
					<?php
					if ($admin) {
						print_link_button(plugin_page("config_page"), plugin_lang_get('config'));
					}
					?></span>
				</div>
				<div class="widget-body">
					<div class="widget-main no-padding">
						<div cass="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="row-category">
										<td width="5%"> </td>
										<td><?php echo plugin_lang_get('rule_project') ?></td>
										<td><?php echo plugin_lang_get('rule_regexp') ?></td>
										<td><?php echo plugin_lang_get('rule_replace') ?></td>
									</tr>
								</thead>
								<tbody>
									<?php echo form_security_field("plugin_AutoLink_rule_list_action") ?>
									<?php foreach($rules as $rule): ?>
										<tr>
											<td class="center"><label><input type="checkbox" name="rule_list[]" value="<?php echo $rule->id ?>" class="ace"/><span class="lbl"></span></label></td>
											<td><?php echo project_get_name($rule->project_id) ?></td>
											<td><?php echo string_html_specialchars($rule->regexp) ?></td>
											<td><?php echo string_html_specialchars($rule->replace) ?></td>
										</tr>
									<?php endforeach ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="widget-toolbox padding-8 clearfix">
						<select class="rules_select_action" name="action">
							<option value="edit"><?php echo plugin_lang_get("action_edit") ?></option>
							<option value="delete"><?php echo plugin_lang_get("action_delete") ?></option>
						</select>
						<input class="rules_select_submit btn btn-primary btn-white btn-round" type="submit" value="<?php echo plugin_lang_get('action_go') ?>">
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="space-10"></div>
	<div class="form-container">
		<form action="<?php echo plugin_page('rule_create') ?>" method="post">
			<div class="widget-box widget-color-blue2">
				<div class="widget-header widget-header-small">
					<h4 class="widget-title"><?php echo plugin_lang_get("create_title") ?></h4>
				</div>
				<div class="widget-body">
					<div class="widget-main no-padding">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<?php echo form_security_field("plugin_AutoLink_rule_create") ?>
								<tbody>
									<tr>
      <td class="category"><?php echo plugin_lang_get("create_project")?></td>
    <td><select name="project"><?php print_project_option_list(null, true) ?></select></td>
    </tr>
    <tr>
      <td class="category"><?php echo plugin_lang_get("create_regexp") ?></td>
      <td><input name="regexp" type="text" class="form-control" maxlength="500" /></td>
    </tr>
    <tr>
      <td class="category"><?php echo plugin_lang_get("create_replace") ?></td>
      <td><input name="replace" type="text" class="form-control" maxlength="500" /></td>
    </tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="widget-toolbox padding-8 clearfix">
						<input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo plugin_lang_get('action_create') ?>" />
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<?php
layout_page_end();
