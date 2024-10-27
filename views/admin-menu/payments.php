<?php
namespace AdCoinPayments;
defined('ABSPATH') or die;
?>
<div class="wrap">
	<h1><strong><?php _e('AdCoin Payments', 'adcoin-payments');?></strong></h1>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-1">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">

					<form method="post">
						<?php
						$this->payment_list->prepare_items();
						$this->payment_list->display();
						?>
					</form>

				</div>
			</div>
		</div>
		<br class="clear">

	</div>
</div>
