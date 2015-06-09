<?php
/*
Template Name: Purchase Credits
*/
?>

<?php get_header(); ?>

<div class="page-three-columns">

	<div id="secondary" class="widget-area left-widget-area" role="complementary">
		<aside class="widget widget-error">
			<img src="https://avatars3.githubusercontent.com/u/432541?v=3&s=460">
		</aside>
	</div>

	<div id="primary" class="site-content">
	
		<div id="content" role="main">

			<h1 class="entry-title"><?php _e('Select Credits'); ?></h1>
			<br />
			<p><?php _e('How many Credits would you like to buy?'); ?></p>

			<form >
				<select class="form-control" id="credit-selection">
					<option value=""><?php _e('Any amount you choose:'); ?></option>
					<?php foreach(\AlphaSSS\Repositories\Credit::creditList() as $amount):?>
						<option value="<?php echo $amount; ?>"><?php printf(__('%d Credits ($%d USD)'), $amount*100, $amount);?></option>
					<?php endforeach;?>
				</select>
				<br />
				<button id="purchase-credits"><?php _e('Proceed to Payment');?></button>
			</form>

		</div><!-- #content -->
	</div><!-- #primary -->

	<div id="secondary" class="widget-area" role="complementary">
		What are Bitcoins?

		What are Credits?
	</div>

</div><!-- .page-left-column -->
<script type="text/javascript">
	$(document).ready(function(){
		var credit_selection = $('#credit-selection').val();

		if (! credit_selection) {
			$('#purchase-credits').attr('disabled', true);
		}

		$('#credit-selection').change(function(){
			if ($(this).val()) {
				$('#purchase-credits').attr('disabled', false);
			} else {
				$('#purchase-credits').attr('disabled', true);
			}
		});
	});
</script>
<?php get_footer(); ?>