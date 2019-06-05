<span class="ribbon-button-alignment pull-right">
	<span id="refresh"
		  class="btn btn-ribbon"
		  rel="tooltip"
		  data-placement="left"
		  data-original-title="{!! __('jarboe::common.refresh.tooltip') !!}"
		  data-html="true"
		  href="{{ route('reset_panel_settings') }}"
	      onclick="window.location.href = this.getAttribute('href')">
		<i class="fa fa-refresh"></i>
	</span>
</span>

@pushonce('style_files',
<style>
	#refresh {
		cursor: pointer;
	}
</style>)
