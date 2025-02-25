<?php

# get correct id for plugin
$thisfile = basename(__FILE__, ".php");

# register plugin
register_plugin(
	$thisfile, //Plugin id
	'focusReminder', 	//Plugin name
	'1.0', 		//Plugin version
	'Multicolor & Slaveous',  //Plugin author
	'https://www.getsimple-ce.ovh/', //author website
	'Plugin that reminds the user to return to the page', //Plugin description
	'plugins', //page type - on which admin tab to display
	'focusReminder'  //main function (administration)
);

# activate filter 
add_action('theme-footer', 'focusReminderFront');

# add a link in the admin tab 'theme'
add_action('plugins-sidebar', 'createSideMenu', array($thisfile, 'focusReminder Settings'));

# functions
function focusReminderFront(){
	if (file_exists(GSDATAOTHERPATH . 'focusReminder.json')) {
		$fr = file_get_contents(GSDATAOTHERPATH . 'focusReminder.json');
		$fr = json_decode($fr, true);

		echo '
		<script>
			let originalTitle = document.title;
			let scrollMessage = "' . $fr['text'] . ' . . . "; // Scrolling message
			let scrollInterval;

			function startScrollingTitle() {
				let index = 0;
				scrollInterval = setInterval(() => {
					document.title = scrollMessage.substring(index) + scrollMessage.substring(0, index);
					index = (index + 1) % scrollMessage.length;
				}, 250); // Scrolling speed
			}

			function stopScrollingTitle() {
				clearInterval(scrollInterval);
				document.title = originalTitle;
			}

			document.addEventListener("visibilitychange", () => {
				if (document.hidden) {
					startScrollingTitle();
				} else {
					stopScrollingTitle();
				}
			});
		</script>';
	};
}

function focusReminder(){
	if (file_exists(GSDATAOTHERPATH . 'focusReminder.json')) {
		$fr = file_get_contents(GSDATAOTHERPATH . 'focusReminder.json');
		$fr = json_decode($fr, true);
	};

	echo '
	<form method="post" style="box-sizing:border-box; padding:15px; border:solid 1px #ddd; background:#fafafa">
		<h3>FocusReminder Settings</h3>
		<input type="text" placeholder=" `Come back! You\'re missing out!` text" name="focustext" value="' . @$fr['text'] . '" style="width:100%; padding:10px; border-radius:5px; box-sizing:border-box; border:solid 1px #ddd">
		<input type="submit" name="savefocus" style="background:red; padding:10px 15px; color:#fff; display:inline-block; border:none; border-radius:4px; margin-top:20px;">
	</form>
	<div id="paypal" style="padding-top:10px">
			<style>
			.donateButton {
				box-shadow:inset 0px 1px 0px 0px #fff6af;
				background:linear-gradient(to bottom, #ffec64 5%, #ffab23 100%);
				background-color:#ffec64;
				border-radius:6px;
				border:1px solid #ffaa22;
				display:inline-block;
				cursor:pointer;
				color:#333333;
				font-family:Arial;
				font-size:15px;
				font-weight:bold;
				padding:6px 24px;
				text-decoration:none;
				text-shadow:0px 1px 0px #ffee66;
			}
			.donateButton:hover {
				background:linear-gradient(to bottom, #ffab23 5%, #ffec64 100%);
				background-color:#ffab23;
			}
			.donateButton:active {
				position:relative;
				top:1px;
			}
			</style>
			<p><a href="https://getsimple-ce.ovh/donate" target="_blank" class="donateButton">Buy Us A Coffee <svg xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-opacity="0" d="M17 14v4c0 1.66 -1.34 3 -3 3h-6c-1.66 0 -3 -1.34 -3 -3v-4Z"><animate fill="freeze" attributeName="fill-opacity" begin="0.8s" dur="0.5s" values="0;1"/></path><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path stroke-dasharray="48" stroke-dashoffset="48" d="M17 9v9c0 1.66 -1.34 3 -3 3h-6c-1.66 0 -3 -1.34 -3 -3v-9Z"><animate fill="freeze" attributeName="stroke-dashoffset" dur="0.6s" values="48;0"/></path><path stroke-dasharray="14" stroke-dashoffset="14" d="M17 9h3c0.55 0 1 0.45 1 1v3c0 0.55 -0.45 1 -1 1h-3"><animate fill="freeze" attributeName="stroke-dashoffset" begin="0.6s" dur="0.2s" values="14;0"/></path><mask id="lineMdCoffeeHalfEmptyFilledLoop0"><path stroke="#fff" d="M8 0c0 2-2 2-2 4s2 2 2 4-2 2-2 4 2 2 2 4M12 0c0 2-2 2-2 4s2 2 2 4-2 2-2 4 2 2 2 4M16 0c0 2-2 2-2 4s2 2 2 4-2 2-2 4 2 2 2 4"><animateMotion calcMode="linear" dur="3s" path="M0 0v-8" repeatCount="indefinite"/></path></mask><rect width="24" height="0" y="7" fill="currentColor" mask="url(#lineMdCoffeeHalfEmptyFilledLoop0)"><animate fill="freeze" attributeName="y" begin="0.8s" dur="0.6s" values="7;2"/><animate fill="freeze" attributeName="height" begin="0.8s" dur="0.6s" values="0;5"/></rect></g></svg></a></p>
		</div>
	';

	if (isset($_POST['savefocus'])) {
		$focusReminder = [];
		$focusReminder['text'] = $_POST['focustext'];
		$focusReminder = json_encode($focusReminder);
		file_put_contents(GSDATAOTHERPATH . 'focusReminder.json', $focusReminder);
		echo ("<meta http-equiv='refresh' content='0'>");
	};
}
?>