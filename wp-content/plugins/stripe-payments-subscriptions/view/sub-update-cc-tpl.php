<?php

class ASP_Sub_Update_CC_Tpl {

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
	<title><?php esc_html_e( 'Update Credit Card', 'asp-sub' ); ?></title>
	<link rel="stylesheet" href="%_plugin_url_%css/pure-min.css?ver=%_addon_ver_%">
	<link rel="stylesheet" href="%_plugin_url_%css/asp-sub-update-cc.css?ver=%_addon_ver_%">
		<?php
		$icon = get_site_icon_url();
		if ( $icon ) {
			printf( '<link rel="icon" href="%s" />' . "\r\n", esc_url( $icon ) );
		}
		?>
</head>

<body>
	<div class="content-wrapper">
		<div class="content">
			<div class="content-center">
				<div id="content">
					<div id="smoke-screen">
						<span id="btn-spinner" class="small-spinner"></span>
					</div>
					%_content_%
				</div>
			</div>
		</div>
	</div>
	%_js_vars_%
	<script>
	var content = document.getElementById("content");
	if (isErr) {
		content.className = "error";
	}
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
