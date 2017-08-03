<script type="text/html" id="categorytemplate">
	<li id="<%= id %>" class="selectcategory"><button><%= title %> (<%= amount %>)</button></li>
</script>
<script type="text/html" id="widgettemplate">
	<div class="ui-widget ui-corner-all ui-widget-content widget" id="<%= id %>" title="<%= title %>">
		<div class="ui-widget-header ui-corner-all widgetheader">
			<span class="widgettitle"><%= title %></span>
			<span class="right icons">
				<span class="ui-icon ui-icon-newwin widgetopenfullscreen"></span>
				<span class="ui-icon ui-icon-arrowthickstop-1-s menutrigger"></span>
				<span class="hiddenmenu">
					<ul style="top: 13px;" class="hidden controls ui-widget-header">
						<li class="widgetClose">
							<span class="ui-icon ui-icon-minus"></span>
							<a class="minimization" href="#"><?php echo $this->lang->line("Minimize"); ?></a>
						</li>
						<li class="widgetOpen">
							<span class="ui-icon ui-icon-extlink"></span>
							<a class="minimization" href="#"><?php echo $this->lang->line("Maximize"); ?></a>
						</li>
						<li class="widgetDelete">
							<span class="ui-icon ui-icon-close"></span>
							<a class="delete" href="#"><?php echo $this->lang->line("delete"); ?></a>
						</li>
						<!-- This could be implemented -->
						<!--
						<li class="widgetEdit">
							<span class="ui-icon ui-icon-tag"></span>
							<a class="no_target" href="#"><?php echo $this->lang->line("edit"); ?></a>
						</li>
						-->
						<li class="widgetRefresh">
							<span class="ui-icon ui-icon-arrowrefresh-1-w"></span>
							<a class="no_target" href="#"><?php echo $this->lang->line("Refresh"); ?></a>
						</li>
					</ul>
				</span>
			</span>
		</div>
		<div class="widgetcontent">
		</div>
	</div>
</script>

<script type="text/html" id="selectlayouttemplate">
	<li class="layoutchoice" id="<%= id %>" style="background-image: url('<%= image %>')"></li>
</script>

<script type="text/html" id="addwidgettemplate">

	<li class="widgetitem">
		<img src="<%= image %>" alt="" height="60" width="120">
		<div class="add-button">
				<input class="macro-button-add addwidget" id="addwidget<%= id %>" value="<?php echo $this->lang->line("Add it Now"); ?>" type="button"><br>
				<input class="macro-hidden-uri" value="<%= url %>" type="hidden">
		</div>
		<!-- // .add-button -->
		<h3><a href=""><%= title %></a></h3>

		<p>By <%= creator %></p>
		<p><%= description %></p>
	</li>

</script>

<div class="dialog" id="addwidgetdialog" title="Widget Directory">
	<ul class="categories">
	</ul>

	<div class="panel-body">
		<ol id="category-all" class="widgets">
		</ol>
	</div>
</div>
<div class="dialog" id="editLayout" title="Edit layout">
	<div class="panel-body" id="layout-dialog">
			<p><strong><?php echo $this->lang->line("Choose dashboard layout"); ?></strong></p>
			<ul class="layoutselection">
			</ul>
	</div>
</div>
