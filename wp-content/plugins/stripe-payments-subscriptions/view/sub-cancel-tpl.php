<?php

class ASP_Sub_Cancel_Tpl {

	private $tpl;
	private $cont_err;
	private $vals;

	public function __construct() {
		$this->get_tpl();
	}

	public function display_tpl( $is_error = false ) {
		$res = $this->tpl;
		if ( $is_error ) {
			$this->vals['js_vars'] = '<script> var isErr=true;</script>';
		} else {
			$this->vals['js_vars'] = '<script> var isErr=false;</script>';
		}
		foreach ( $this->vals as $key => $val ) {
			$res = str_replace( '%_' . $key . '_%', $val, $res );
		}
		echo $res;
	}

	public function set_vals( $vals ) {
		$this->vals = $vals;
	}

	private function get_tpl() {
		ob_start();
		?>
	<!doctype html>

	<html>
		<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php _e( 'Subscription Cancelation', 'asp-sub' ); ?></title>
		<link rel="stylesheet" href="%_plugin_url_%css/pure-min.css">
		<style>
			.content-wrapper{
			display: block;
			left: 0;
			height: 100%;
			position: fixed;
			top: 0;
			width: 100%;
			}
			.content{
			display: table;
			height: 100%;
			margin-left: auto;
			margin-right: auto;
			text-align: center;
			width: 100%;
			}
			#content.error {
			color: red;
			}
			.content-center{
			display: table-cell;
			height: 100%;
			vertical-align: middle;
			width: 100%;
			}
			#cancel-btn, #css-spinner-cont {
			display: none;
			}
			.css-spinner {
			vertical-align: middle;
			display: inline-block;
			position: relative;
			width: 35px;
			height: 35px;
			}
			.css-spinner div {
			box-sizing: border-box;
			display: block;
			position: absolute;
			width: 26px;
			height: 26px;
			margin: 3px;
			border: 3px solid #0078e7;
			border-radius: 50%;
			animation: css-spinner 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
			border-color: #0078e7 transparent transparent transparent;
			}
			.css-spinner div:nth-child(1) {
			animation-delay: -0.45s;
			}
			.css-spinner div:nth-child(2) {
			animation-delay: -0.3s;
			}
			.css-spinner div:nth-child(3) {
			animation-delay: -0.15s;
			}
			@keyframes css-spinner {
			0% {
				transform: rotate(0deg);
			}
			100% {
				transform: rotate(360deg);
			}
			}
		</style>
		</head>
		<body>
		<div class="content-wrapper">
			<div class="content">
			<div class="content-center">
				<div id="content">%_content_%</div>
				<p></p>
				<div>
				<button id="cancel-btn" class="pure-button pure-button-primary"><?php _e( 'Yes, cancel', 'asp-sub' ); ?></button>
				<div id="css-spinner-cont"><div class="css-spinner"><div></div><div></div><div></div><div></div></div></div>
				</div>
			</div>
			</div>
		</div>
		%_js_vars_%
		<script>
			var button = document.getElementById("cancel-btn");
			var spinner = document.getElementById("css-spinner-cont");
			var content = document.getElementById("content");
			if (!isErr) {
			button.style.cssText = "display: initial;";
			} else {
			content.className = "error";
			}
			button.addEventListener("click", function (e) {
			button.hidden = true;
			spinner.style.cssText = "display: initial;";
			var xhr = new XMLHttpRequest();
			xhr.open('POST', '<?php echo admin_url( 'admin-ajax.php' ); ?>');
			xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xhr.onload = function () {
				spinner.style.cssText = "display: none;";
				if (xhr.status === 200) {
				var res = JSON.parse(xhr.responseText);
				if (!res.success) {
					content.className = "error";
				}
				content.innerHTML = res.msg;
				} else {
				content.className = "error";
				content.innerHTML = '<?php _e( 'Error occurred. Error code:', 'asp-sub' ); ?> ' + xhr.status;
				}
			};
			xhr.send('action=asp_cancel_sub&subId=%_sub_token_%');
			}, false);
		</script>
		</body>
	</html>

		<?php
		$this->tpl = ob_get_clean();

		ob_start();
		?>
	<div class="error">%_err_msg_%</div>
		<?php
		$this->cont_err = ob_get_clean();
	}

}
