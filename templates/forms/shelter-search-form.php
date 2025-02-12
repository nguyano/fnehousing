<form method="post" id="fnehd-shelter-search-form">
	<div class="input-group border border-info rounded-pill pl-2 pr-2">
		<input type="hidden" name="action" value="fnehd_shelter_search">
		<?php wp_nonce_field('fnehd_shelter_search_nonce', 'nonce'); ?>
		<input type="hidden" name="search_phrase" id="fnehd-shelter-search-phrase">
		<a class="mt-1 infopop" tabindex="0" data-toggle="popover" data-trigger="focus" data-placement="bottom" data-content="<?= __('Search shelters with phrases from either the payer or earner usernames', 'fnehousing'); ?>" title="" data-original-title="Help here"><?= __("Info", "fnehousing"); ?></a>
		<input id="fnehd-shelter-search-input" name="shelter_search" type="search" placeholder="Search shelters here..." aria-describedby="button-addon3" class="form-control bg-none border-0 fnehd-search-input" required>
		<div class="input-group-append border-0">
		  <button id="button-addon3" type="submit" class="btn btn-link fnehd-text-info">
		  <i class="fa fa-search"></i> &nbsp;<b>|</b> </button>
		  <button type="reset" class="btn btn-link fnehd-text-info">
		  <i class="fa fa-shuffle"></i></button>
		</div>
	</div>
</form>