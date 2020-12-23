<?php 

function tommusrhodus_framework_add_elementor_widget_categories( $elements_manager ) {
	
	$elements_manager->add_category(
		'wingman-elements',
		array(
			'title' => 'Wingman Elements'
		)
	);

}
add_action( 'elementor/elements/categories_registered', 'tommusrhodus_framework_add_elementor_widget_categories', 10, 1 );

function tommusrhodus_framework_add_elementor_custom_icons( $controls_registry ) {
	
	// Append new icons
	$new_icons = array(
		'icon-add-to-list' => 'add-to-list',
		'icon-add-user' => 'add-user',
		'icon-address' => 'address',
		'icon-adjust' => 'adjust',
		'icon-air' => 'air',
		'icon-aircraft-landing' => 'aircraft-landing',
		'icon-aircraft-take-off' => 'aircraft-take-off',
		'icon-aircraft' => 'aircraft',
		'icon-align-bottom' => 'align-bottom',
		'icon-align-horizontal-middle' => 'align-horizontal-middle',
		'icon-align-left' => 'align-left',
		'icon-align-right' => 'align-right',
		'icon-align-top' => 'align-top',
		'icon-align-vertical-middle' => 'align-vertical-middle',
		'icon-archive' => 'archive',
		'icon-area-graph' => 'area-graph',
		'icon-arrow-bold-down' => 'arrow-bold-down',
		'icon-arrow-bold-left' => 'arrow-bold-left',
		'icon-arrow-bold-right' => 'arrow-bold-right',
		'icon-arrow-bold-up' => 'arrow-bold-up',
		'icon-arrow-down' => 'arrow-down',
		'icon-arrow-left' => 'arrow-left',
		'icon-arrow-long-down' => 'arrow-long-down',
		'icon-arrow-long-left' => 'arrow-long-left',
		'icon-arrow-long-right' => 'arrow-long-right',
		'icon-arrow-long-up' => 'arrow-long-up',
		'icon-arrow-right' => 'arrow-right',
		'icon-arrow-up' => 'arrow-up',
		'icon-arrow-with-circle-down' => 'arrow-with-circle-down',
		'icon-arrow-with-circle-left' => 'arrow-with-circle-left',
		'icon-arrow-with-circle-right' => 'arrow-with-circle-right',
		'icon-arrow-with-circle-up' => 'arrow-with-circle-up',
		'icon-attachment' => 'attachment',
		'icon-awareness-ribbon' => 'awareness-ribbon',
		'icon-back-in-time' => 'back-in-time',
		'icon-back' => 'back',
		'icon-bar-graph' => 'bar-graph',
		'icon-battery' => 'battery',
		'icon-beamed-note' => 'beamed-note',
		'icon-bell' => 'bell',
		'icon-blackboard' => 'blackboard',
		'icon-block' => 'block',
		'icon-book' => 'book',
		'icon-bookmark' => 'bookmark',
		'icon-bookmarks' => 'bookmarks',
		'icon-bowl' => 'bowl',
		'icon-box' => 'box',
		'icon-briefcase' => 'briefcase',
		'icon-browser' => 'browser',
		'icon-brush' => 'brush',
		'icon-bucket' => 'bucket',
		'icon-bug' => 'bug',
		'icon-cake' => 'cake',
		'icon-calculator' => 'calculator',
		'icon-calendar' => 'calendar',
		'icon-camera' => 'camera',
		'icon-ccw' => 'ccw',
		'icon-chat' => 'chat',
		'icon-check' => 'check',
		'icon-chevron-down' => 'chevron-down',
		'icon-chevron-left' => 'chevron-left',
		'icon-chevron-right' => 'chevron-right',
		'icon-chevron-small-down' => 'chevron-small-down',
		'icon-chevron-small-left' => 'chevron-small-left',
		'icon-chevron-small-right' => 'chevron-small-right',
		'icon-chevron-small-up' => 'chevron-small-up',
		'icon-chevron-thin-down' => 'chevron-thin-down',
		'icon-chevron-thin-left' => 'chevron-thin-left',
		'icon-chevron-thin-right' => 'chevron-thin-right',
		'icon-chevron-thin-up' => 'chevron-thin-up',
		'icon-chevron-up' => 'chevron-up',
		'icon-chevron-with-circle-down' => 'chevron-with-circle-down',
		'icon-chevron-with-circle-left' => 'chevron-with-circle-left',
		'icon-chevron-with-circle-right' => 'chevron-with-circle-right',
		'icon-chevron-with-circle-up' => 'chevron-with-circle-up',
		'icon-circle-with-cross' => 'circle-with-cross',
		'icon-circle-with-minus' => 'circle-with-minus',
		'icon-circle-with-plus' => 'circle-with-plus',
		'icon-circle' => 'circle',
		'icon-circular-graph' => 'circular-graph',
		'icon-clapperboard' => 'clapperboard',
		'icon-classic-computer' => 'classic-computer',
		'icon-clipboard' => 'clipboard',
		'icon-clock' => 'clock',
		'icon-cloud' => 'cloud',
		'icon-code' => 'code',
		'icon-cog' => 'cog',
		'icon-colours' => 'colours',
		'icon-compass' => 'compass',
		'icon-controller-fast-backward' => 'controller-fast-backward',
		'icon-controller-fast-forward' => 'controller-fast-forward',
		'icon-controller-jump-to-start' => 'controller-jump-to-start',
		'icon-controller-next' => 'controller-next',
		'icon-controller-paus' => 'controller-paus',
		'icon-controller-play' => 'controller-play',
		'icon-controller-record' => 'controller-record',
		'icon-controller-stop' => 'controller-stop',
		'icon-controller-volume' => 'controller-volume',
		'icon-copy' => 'copy',
		'icon-creative-commons-attribution' => 'creative-commons-attribution',
		'icon-creative-commons-noderivs' => 'creative-commons-noderivs',
		'icon-creative-commons-noncommercial-eu' => 'creative-commons-noncommercial-eu',
		'icon-creative-commons-noncommercial-us' => 'creative-commons-noncommercial-us',
		'icon-creative-commons-public-domain' => 'creative-commons-public-domain',
		'icon-creative-commons-remix' => 'creative-commons-remix',
		'icon-creative-commons-share' => 'creative-commons-share',
		'icon-creative-commons-sharealike' => 'creative-commons-sharealike',
		'icon-creative-commons' => 'creative-commons',
		'icon-credit-card' => 'credit-card',
		'icon-credit' => 'credit',
		'icon-crop' => 'crop',
		'icon-cross' => 'cross',
		'icon-cup' => 'cup',
		'icon-cw' => 'cw',
		'icon-cycle' => 'cycle',
		'icon-database' => 'database',
		'icon-dial-pad' => 'dial-pad',
		'icon-direction' => 'direction',
		'icon-document-landscape' => 'document-landscape',
		'icon-document' => 'document',
		'icon-documents' => 'documents',
		'icon-dot-single' => 'dot-single',
		'icon-dots-three-horizontal' => 'dots-three-horizontal',
		'icon-dots-three-vertical' => 'dots-three-vertical',
		'icon-dots-two-horizontal' => 'dots-two-horizontal',
		'icon-dots-two-vertical' => 'dots-two-vertical',
		'icon-download' => 'download',
		'icon-drink' => 'drink',
		'icon-drive' => 'drive',
		'icon-drop' => 'drop',
		'icon-edit' => 'edit',
		'icon-email' => 'email',
		'icon-emoji-flirt' => 'emoji-flirt',
		'icon-emoji-happy' => 'emoji-happy',
		'icon-emoji-neutral' => 'emoji-neutral',
		'icon-emoji-sad' => 'emoji-sad',
		'icon-erase' => 'erase',
		'icon-eraser' => 'eraser',
		'icon-export' => 'export',
		'icon-eye-with-line' => 'eye-with-line',
		'icon-eye' => 'eye',
		'icon-feather' => 'feather',
		'icon-fingerprint' => 'fingerprint',
		'icon-flag' => 'flag',
		'icon-flash' => 'flash',
		'icon-flashlight' => 'flashlight',
		'icon-flat-brush' => 'flat-brush',
		'icon-flow-branch' => 'flow-branch',
		'icon-flow-cascade' => 'flow-cascade',
		'icon-flow-line' => 'flow-line',
		'icon-flow-parallel' => 'flow-parallel',
		'icon-flow-tree' => 'flow-tree',
		'icon-flower' => 'flower',
		'icon-folder-images' => 'folder-images',
		'icon-folder-music' => 'folder-music',
		'icon-folder-video' => 'folder-video',
		'icon-folder' => 'folder',
		'icon-forward' => 'forward',
		'icon-funnel' => 'funnel',
		'icon-game-controller' => 'game-controller',
		'icon-gauge' => 'gauge',
		'icon-globe' => 'globe',
		'icon-graduation-cap' => 'graduation-cap',
		'icon-grid' => 'grid',
		'icon-hair-cross' => 'hair-cross',
		'icon-hand' => 'hand',
		'icon-heart-outlined' => 'heart-outlined',
		'icon-heart' => 'heart',
		'icon-help-with-circle' => 'help-with-circle',
		'icon-help' => 'help',
		'icon-home' => 'home',
		'icon-hour-glass' => 'hour-glass',
		'icon-image-inverted' => 'image-inverted',
		'icon-image' => 'image',
		'icon-images' => 'images',
		'icon-inbox' => 'inbox',
		'icon-infinity' => 'infinity',
		'icon-info-with-circle' => 'info-with-circle',
		'icon-info' => 'info',
		'icon-install' => 'install',
		'icon-key' => 'key',
		'icon-keyboard' => 'keyboard',
		'icon-lab-flask' => 'lab-flask',
		'icon-landline' => 'landline',
		'icon-language' => 'language',
		'icon-laptop' => 'laptop',
		'icon-layers' => 'layers',
		'icon-leaf' => 'leaf',
		'icon-level-down' => 'level-down',
		'icon-level-up' => 'level-up',
		'icon-lifebuoy' => 'lifebuoy',
		'icon-light-bulb' => 'light-bulb',
		'icon-light-down' => 'light-down',
		'icon-light-up' => 'light-up',
		'icon-line-graph' => 'line-graph',
		'icon-link' => 'link',
		'icon-list' => 'list',
		'icon-location-pin' => 'location-pin',
		'icon-location' => 'location',
		'icon-lock-open' => 'lock-open',
		'icon-lock' => 'lock',
		'icon-log-out' => 'log-out',
		'icon-login' => 'login',
		'icon-loop' => 'loop',
		'icon-magnet' => 'magnet',
		'icon-magnifying-glass' => 'magnifying-glass',
		'icon-mail' => 'mail',
		'icon-man' => 'man',
		'icon-map' => 'map',
		'icon-mask' => 'mask',
		'icon-medal' => 'medal',
		'icon-megaphone' => 'megaphone',
		'icon-menu' => 'menu',
		'icon-merge' => 'merge',
		'icon-message' => 'message',
		'icon-mic' => 'mic',
		'icon-minus' => 'minus',
		'icon-mobile' => 'mobile',
		'icon-modern-mic' => 'modern-mic',
		'icon-moon' => 'moon',
		'icon-mouse-pointer' => 'mouse-pointer',
		'icon-mouse' => 'mouse',
		'icon-music' => 'music',
		'icon-network' => 'network',
		'icon-new-message' => 'new-message',
		'icon-new' => 'new',
		'icon-news' => 'news',
		'icon-newsletter' => 'newsletter',
		'icon-note' => 'note',
		'icon-notification' => 'notification',
		'icon-notifications-off' => 'notifications-off',
		'icon-old-mobile' => 'old-mobile',
		'icon-old-phone' => 'old-phone',
		'icon-open-book' => 'open-book',
		'icon-palette' => 'palette',
		'icon-paper-plane' => 'paper-plane',
		'icon-pencil' => 'pencil',
		'icon-phone' => 'phone',
		'icon-pie-chart' => 'pie-chart',
		'icon-pin' => 'pin',
		'icon-plus' => 'plus',
		'icon-popup' => 'popup',
		'icon-power-plug' => 'power-plug',
		'icon-price-ribbon' => 'price-ribbon',
		'icon-price-tag' => 'price-tag',
		'icon-print' => 'print',
		'icon-progress-empty' => 'progress-empty',
		'icon-progress-full' => 'progress-full',
		'icon-progress-one' => 'progress-one',
		'icon-progress-two' => 'progress-two',
		'icon-publish' => 'publish',
		'icon-quote' => 'quote',
		'icon-radio' => 'radio',
		'icon-remove-user' => 'remove-user',
		'icon-reply-all' => 'reply-all',
		'icon-reply' => 'reply',
		'icon-resize-100%' => 'resize-100%',
		'icon-resize-full-screen' => 'resize-full-screen',
		'icon-retweet' => 'retweet',
		'icon-rocket' => 'rocket',
		'icon-round-brush' => 'round-brush',
		'icon-rss' => 'rss',
		'icon-ruler' => 'ruler',
		'icon-save' => 'save',
		'icon-scissors' => 'scissors',
		'icon-select-arrows' => 'select-arrows',
		'icon-share-alternative' => 'share-alternative',
		'icon-share' => 'share',
		'icon-shareable' => 'shareable',
		'icon-shield' => 'shield',
		'icon-shop' => 'shop',
		'icon-shopping-bag' => 'shopping-bag',
		'icon-shopping-basket' => 'shopping-basket',
		'icon-shopping-cart' => 'shopping-cart',
		'icon-shuffle' => 'shuffle',
		'icon-signal' => 'signal',
		'icon-sound-mix' => 'sound-mix',
		'icon-sound-mute' => 'sound-mute',
		'icon-sound' => 'sound',
		'icon-sports-club' => 'sports-club',
		'icon-spreadsheet' => 'spreadsheet',
		'icon-squared-cross' => 'squared-cross',
		'icon-squared-minus' => 'squared-minus',
		'icon-squared-plus' => 'squared-plus',
		'icon-star-outlined' => 'star-outlined',
		'icon-star' => 'star',
		'icon-stopwatch' => 'stopwatch',
		'icon-suitcase' => 'suitcase',
		'icon-swap' => 'swap',
		'icon-sweden' => 'sweden',
		'icon-switch' => 'switch',
		'icon-tablet-mobile-combo' => 'tablet-mobile-combo',
		'icon-tablet' => 'tablet',
		'icon-tag' => 'tag',
		'icon-text-document-inverted' => 'text-document-inverted',
		'icon-text-document' => 'text-document',
		'icon-text' => 'text',
		'icon-thermometer' => 'thermometer',
		'icon-thumbs-down' => 'thumbs-down',
		'icon-thumbs-up' => 'thumbs-up',
		'icon-thunder-cloud' => 'thunder-cloud',
		'icon-ticket' => 'ticket',
		'icon-time-slot' => 'time-slot',
		'icon-tools' => 'tools',
		'icon-traffic-cone' => 'traffic-cone',
		'icon-trash' => 'trash',
		'icon-tree' => 'tree',
		'icon-triangle-down' => 'triangle-down',
		'icon-triangle-left' => 'triangle-left',
		'icon-triangle-right' => 'triangle-right',
		'icon-triangle-up' => 'triangle-up',
		'icon-trophy' => 'trophy',
		'icon-tv' => 'tv',
		'icon-typing' => 'typing',
		'icon-uninstall' => 'uninstall',
		'icon-unread' => 'unread',
		'icon-untag' => 'untag',
		'icon-upload-to-cloud' => 'upload-to-cloud',
		'icon-upload' => 'upload',
		'icon-user' => 'user',
		'icon-users' => 'users',
		'icon-v-card' => 'v-card',
		'icon-video-camera' => 'video-camera',
		'icon-video' => 'video',
		'icon-vinyl' => 'vinyl',
		'icon-voicemail' => 'voicemail',
		'icon-wallet' => 'wallet',
		'icon-warning' => 'warning',
		'icon-water' => 'water'
	);

	$elementor_icons = $controls_registry->get_control( 'icon' )->get_settings( 'options' );
	$icons           = array_merge( $new_icons, $elementor_icons );
	
	// Sort alphabetical
	asort( $icons );
	
	$controls_registry->get_control( 'icon' )->set_settings( 'options', $icons );
	
}
add_action( 'elementor/controls/controls_registered', 'tommusrhodus_framework_add_elementor_custom_icons', 10, 1 );