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
access_ensure_global_level(config_get("manage_plugin_threshold"));

layout_page_header( plugin_lang_get( 'title' ) );
layout_page_begin();

   print_manage_menu();
?>
<div class="col-md-12 col-xs-12">
	<div class="space-10"></div>
	<div id="formatting-config-div" class="form-container">
		<form action="<?php echo plugin_page('config') ?>" method="post" class="form-inline">
			<div class="widget-box widget-color-blue2">
				<div class="widget-header widget-header-small"><h4 class="widget-title lighter"><?php echo plugin_lang_get("config_title") ?></h4></div>
				<div class="widget-body">
					<div class="widget-main no-padding">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<?php echo form_security_field("plugin_AutoLink_config") ?>
								<tr>
		<td class="category"><?php echo plugin_lang_get('edit_threshold') ?></td>
		<td><select name="edit_threshold" class="input-sm"><?php print_enum_string_option_list('access_levels', plugin_config_get('edit_threshold')) ?></select></td>
	    </tr>
	    <tr>
    <td class="category"><?php echo plugin_lang_get('process_text') ?></td>
    <td><label><input type="checkbox" class="ace" name="process_text" <?php echo ( plugin_config_get('process_text') ? 'checked="checked" ' : '') ?>/><span class="lbl"></span></label></td>
  </tr>
  <tr>
    <td class="category"><?php echo plugin_lang_get('process_urls') ?></td>
    <td><label><input type="checkbox" class="ace" name="process_urls" <?php echo ( plugin_config_get('process_urls') ? 'checked="checked" ' : '') ?>/><span class="lbl"></span></label></td>
  </tr>
  <tr>
    <td class="category"><?php echo plugin_lang_get('process_buglinks') ?></td>
    <td><label><input type="checkbox" class="ace" name="process_buglinks" <?php echo ( plugin_config_get('process_buglinks') ? 'checked="checked" ' : '') ?>/><span class="lbl"></span></label></td>
  </tr>
  <tr>
    <td class="category"><?php echo plugin_lang_get('process_vcslinks') ?></td>
    <td><label><input type="checkbox" class="ace" name="process_vcslinks" <?php echo ( plugin_config_get('process_vcslinks') ? 'checked="checked" ' : '') ?>/><span class="lbl"></span></label></td>
  </tr>
							</table>
						</div>
					</div>
					<div class="widget-toolbox padding-8 clearfix">
						<input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo plugin_lang_get('action_update') ?>" />
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php
layout_page_end();
