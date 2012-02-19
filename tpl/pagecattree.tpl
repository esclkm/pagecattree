<!-- BEGIN: MAIN -->
<ul class="bullets">
	<!-- BEGIN: CATS -->
	<li <!-- IF {ROW_SUBCAT} -->class="pcthavesubelems"<!-- ENDIF -->><a href="{ROW_HREF}">{ROW_TITLE}</a>
		<!-- IF {ROW_SUBCAT} -->
		<div class="pctsubelems">
			{ROW_SUBCAT}
		</div>
		<!-- ENDIF -->
	</li>
	<!-- END: CATS -->
</ul>
<!-- END: MAIN -->