<!-- BEGIN: MAIN -->
<ul class="bullets">
	<!-- BEGIN: CATS -->
	<li class="<!-- IF {ROW_SUBCAT} -->pcthavesubelems <!-- ENDIF -->{ROW_CLASS}"><a href="{ROW_HREF}">{ROW_TITLE}</a>
		<!-- IF {ROW_SUBCAT} -->
		<div class="pctsubelems">
			{ROW_SUBCAT}
		</div>
		<!-- ENDIF -->
		{ROW_PAGES}
	</li>
	<!-- END: CATS -->
</ul>
<!-- END: MAIN -->
<!-- BEGIN: PAGES -->
<ul class="bullets">
	<!-- BEGIN: PAGE -->
	<li class="<!-- IF {ROW_SUBCAT} -->pcthavesubelems <!-- ENDIF -->{ROW_CLASS}"><a href="{ROW_HREF}">{ROW_TITLE}</a></li>
	<!-- END: PAGE -->
</ul>
<!-- END: PAGES -->
