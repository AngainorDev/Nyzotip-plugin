<div class="wrap">
	<h2>Nyzotip config</h2>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<label for="receiver_id">Receiver id (id__ nyzostring):</label><br />
		<input type="text" name="receiver_id" value="<?php echo esc_attr($nyzotip_options['receiver_id']); ?>" >
		<br>&nbsp;<br />
		<label for="client_url">Client url:</label><br />
		<input type="text" name="client_url" value="<?php echo esc_attr(esc_url($nyzotip_options['client_url'])); ?>" >
		<br>&nbsp;<br />
		<label for="stealth_tip">Add stealth code to each page:</label><br>
		<label for="stealth_tip1">Yes:</label>		
		<input type="radio" name="stealth_tip" id="stealth_tip1" value="1" <?php if($nyzotip_options['stealth_tip']){ echo checked;}; ?>>
		<label for="stealth_tip0">No: </label>
		<input type="radio" name="stealth_tip" id="stealth_tip0" value="0" <?php if(!$nyzotip_options['stealth_tip']){ echo checked;}; ?>>
		<br>&nbsp;<br />
		<button>Save</button>
	</form>
</div>
