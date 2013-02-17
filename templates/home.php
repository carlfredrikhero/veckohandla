<?php require 'header.php'; ?>
<div class="container" id="shoplistapp">
	<div class="sidebar">
	</div>
	<div class="content">
		<div class="period">
			<input type="hidden" id="week" />
			<a href="#" class="select-week">Select start date</a> <small>(the period will consist of your start date +7 days)</small>
		</div>
		<ul id="shopping-list">
			<li>
				<input type="checkbox" disabled="disabled" /> 
				<input class="item-label" id="add" type="text" placeholder="Lägg till vara..."/>
			</li>
		</ul>
	</div>
</div>
	
<script type="text/template" id="item-template">
<input class="item-done" type="checkbox" <%= done ? 'checked="checked"' : '' %> /> <input class="item-label" type="text" value="<%= label %>" placeholder="Lägg till vara..."/> <input class="item-tag" type="text" value="<%= tag %>" placeholder="Lägg till en kategori"/>
</script>

<script type="text/template" id="tag-header-template">
	<li class="tag-header"><input type="text" value="<%= headertext %>" data-org-value="<%= headertext %>" /></li>
</script>
<?php require 'footer.php'; ?>