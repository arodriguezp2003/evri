<?php 

/**
 * tommusrhodus_register_nav_menus()
 * 
 * Register the menu areas for the theme.
 * 
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_register_nav_menus' ) )){
	function tommusrhodus_register_nav_menus() {
		register_nav_menus( 
			array(
				'primary'    => esc_html__( 'Standard Navigation',  'uptime' )
			) 
		);
	}
	add_action( 'init', 'tommusrhodus_register_nav_menus' );
}

/**
 * tommusrhodus_register_sidebars()
 * 
 * Register the widget areas for the theme.
 * 
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_register_sidebars' ) )){
	function tommusrhodus_register_sidebars() {
		
		register_sidebar(
			array(
				'id'            => 'primary',
				'name'          => esc_html__( 'Blog Sidebar', 'uptime' ),
				'before_widget' => '<div id="%1$s" class="widget mb-4 %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5>',
				'after_title'   => '</h5>'
			)
		);
		
		register_sidebar(
			array(
				'id'            => 'default_footer_1',
				'name'          => esc_html__( 'Default Footer Column 1', 'uptime' ),
				'description'   => esc_html__( 'If this is set, your footer will be 1 column', 'uptime' ),
				'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5>',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'id'            => 'default_footer_2',
				'name'          => esc_html__( 'Default Footer Column 2', 'uptime' ),
				'description'   => esc_html__( 'If this is set, your footer will be 2 columns', 'uptime' ),
				'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5>',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'id'            => 'default_footer_3',
				'name'          => esc_html__( 'Default Footer Column 3', 'uptime' ),
				'description'   => esc_html__( 'If this is set, your footer will be 3 columns', 'uptime' ),
				'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5>',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'id'            => 'vanilla_footer_1',
				'name'          => esc_html__( 'Vanilla Footer Column 1', 'uptime' ),
				'description'   => esc_html__( 'If this is set, your vanilla footer will be 1 column', 'uptime' ),
				'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5>',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'id'            => 'vanilla_footer_2',
				'name'          => esc_html__( 'Vanilla Footer Column 2', 'uptime' ),
				'description'   => esc_html__( 'If this is set, your vanilla footer will be 2 columns', 'uptime' ),
				'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5>',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'id'            => 'vanilla_footer_3',
				'name'          => esc_html__( 'Vanilla Footer Column 3', 'uptime' ),
				'description'   => esc_html__( 'If this is set, your vanilla footer will be 3 columns', 'uptime' ),
				'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5>',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'id'            => 'vanilla_footer_4',
				'name'          => esc_html__( 'Vanilla Footer Column 4', 'uptime' ),
				'description'   => esc_html__( 'If this is set, your vanilla footer will be 4 columns', 'uptime' ),
				'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5>',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'id'            => 'centered_footer',
				'name'          => esc_html__( 'Centered Footer', 'uptime' ),
				'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5>',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'id'            => 'short_footer_1',
				'name'          => esc_html__( 'Short Footer Column 1', 'uptime' ),
				'description'   => esc_html__( 'If this is set, your short footer will be 1 column', 'uptime' ),
				'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5>',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'id'            => 'short_footer_2',
				'name'          => esc_html__( 'Short Footer Column 2', 'uptime' ),
				'description'   => esc_html__( 'If this is set, your short footer will be 2 columns', 'uptime' ),
				'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5>',
				'after_title'   => '</h5>'
			)
		);
		
	}
	add_action( 'widgets_init', 'tommusrhodus_register_sidebars' );
}

if ( ! class_exists( 'WP_Bootstrap_Navwalker' ) ) {
	/**
	 * WP_Bootstrap_Navwalker class.
	 *
	 * @extends Walker_Nav_Menu
	 */
	class WP_Bootstrap_Navwalker extends Walker_Nav_Menu {
		
		public $doing_mega_menu = false;
		public $doing_list_menu = false;
		
		/**
		 * Starts the list before the elements are added.
		 *
		 * @since WP 3.0.0
		 *
		 * @see Walker_Nav_Menu::start_lvl()
		 *
		 * @param string   $output Used to append additional content (passed by reference).
		 * @param int      $depth  Depth of menu item. Used for padding.
		 * @param stdClass $args   An object of wp_nav_menu() arguments.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			
			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			
			$indent = str_repeat( $t, $depth );
			
			// Default class to add to the file.
			$classes = array( 'dropdown-menu row' );
			
			/**
			 * Filters the CSS class(es) applied to a menu list element.
			 *
			 * @since WP 4.8.0
			 *
			 * @param array    $classes The CSS classes that are applied to the menu `<ul>` element.
			 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
			 * @param int      $depth   Depth of menu item. Used for padding.
			 */
			$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
			
			if( $this->doing_mega_menu && 0 == $depth ){
				
				$output .= '<div class="dropdown-menu '. get_theme_mod( 'mega_menu_background', 'bg-primary-3 text-light' ) .' border-bottom"><div class="container py-4" data-dropdown-content><div class="row">';
				
			} elseif( $this->doing_mega_menu && 1 == $depth ) {
			
				$output .= '<div>';
				
			} elseif( $this->doing_list_menu && 0 == $depth ) {
			
				$output .= '<div '. $class_names .'><div class="col-auto" data-dropdown-content><div class="bg-white rounded border shadow-lg o-hidden">';
				
			} elseif( $this->doing_list_menu && 1 == $depth ) {
			
				$output .= '<div>';
				
			} else {
			
				$output .= '<div '. $class_names .'><div class="col-auto" data-dropdown-content><div class="dropdown-grid-menu">';
				
			}
			
		}
		
		public function end_lvl( &$output, $depth = 0, $args = array() ) {
			
			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			
			$indent = str_repeat( $t, $depth );

			if( $this->doing_mega_menu && 1 == $depth ) {
				
				$output .= '</div></div>';
				
			} elseif( 1 == $depth ) {
			
				$output .= '</div></div></div></div>';
			
			} else {
			
				$output .= '</div></div></div>';
			
			}

			
			if( $this->doing_mega_menu && 0 == $depth ){
				$this->doing_mega_menu = false;
			}
			
			if( $this->doing_list_menu && 0 == $depth ){
				$this->doing_list_menu = false;
			}
			
		}

		/**
		 * Starts the element output.
		 *
		 * @since WP 3.0.0
		 * @since WP 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
		 *
		 * @see Walker_Nav_Menu::start_el()
		 *
		 * @param string   $output Used to append additional content (passed by reference).
		 * @param WP_Post  $item   Menu item data object.
		 * @param int      $depth  Depth of menu item. Used for padding.
		 * @param stdClass $args   An object of wp_nav_menu() arguments.
		 * @param int      $id     Current item ID.
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			
			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			
			$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$menu_icon = get_post_meta( $item->ID, 'menu-item-custom-icon', true );
			$menu_image = get_post_meta( $item->ID, 'menu-item-custom-image', true );

			/**
			 * Filters the arguments for a single nav menu item.
			 *
			 *  WP 4.4.0
			 *
			 * @param stdClass $args  An object of wp_nav_menu() arguments.
			 * @param WP_Post  $item  Menu item data object.
			 * @param int      $depth Depth of menu item. Used for padding.
			 */
			$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

			// Add .dropdown or .active classes where they are needed.
			if ( isset( $args->has_children ) && $args->has_children ) {
				$classes[] = 'dropdown';
			}
			if ( in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-parent', $classes, true ) ) {
				$classes[] = 'active';
			}

			// Add some additional default classes to the item.
			$classes[] = 'menu-item-' . $item->ID;
			$classes[] = 'nav-item';

			// Allow filtering the classes.
			$classes = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth );
			
			// Check for mega menu
			if( !$this->doing_mega_menu ){
				$this->doing_mega_menu = ( 'mega-menu' == $item->description ) ? true : false;
			}
			if( !$this->doing_list_menu ){
				$this->doing_list_menu = ( 'list-menu' == $item->description ) ? true : false;
			}

			// Form a string of classes in format: class="class_names".
			$class_names = join( ' ', $classes );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			/**
			 * Filters the ID applied to a menu item's list item element.
			 *
			 * @since WP 3.0.1
			 * @since WP 4.1.0 The `$depth` parameter was added.
			 *
			 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
			 * @param WP_Post  $item    The current menu item.
			 * @param stdClass $args    An object of wp_nav_menu() arguments.
			 * @param int      $depth   Depth of menu item. Used for padding.
			 */
			$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
			
			if( 0 == $depth ){
				$output .= $indent . '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement"' . $id . $class_names . '>';
			}

			// initialize array for holding the $atts for the link item.
			$atts = array();

			// Set title from item to the $atts array - if title is empty then
			// default to item title.
			if ( empty( $item->attr_title ) ) {
				$atts['title'] = ! empty( $item->title ) ? strip_tags( $item->title ) : '';
			} else {
				$atts['title'] = $item->attr_title;
			}

			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
			
			// If item has_children add atts to <a>.
			if ( isset( $args->has_children ) && $args->has_children && $args->depth > 1 ) {
				
				if( $this->doing_mega_menu && 1 == $depth ){
					$atts['href'] = ! empty( $item->url ) ? $item->url : '#';
				} else {
					$atts['href'] = '#';
					$atts['data-toggle']   = 'dropdown-grid';
					$atts['aria-haspopup'] = 'true';
					$atts['aria-expanded'] = 'false';
					$atts['class']         = 'dropdown-toggle nav-link';
				}

			} else {
			
				$atts['href'] = ! empty( $item->url ) ? $item->url : '#';
				
				// Items in dropdowns use .dropdown-item instead of .nav-link.
				if ( $this->doing_list_menu ) {
					$atts['class'] = 'list-group-item list-group-item-action d-flex align-items-center p-3';
				} elseif ( $depth > 0 ) {
					$atts['class'] = 'dropdown-item';
				} else {
					$atts['class'] = 'nav-link';
				}
				
				if( isset( $args->has_children ) && !$args->has_children && $atts['target'] !== '_blank' ){
					if( strpos( $atts['href'], '#' ) === false ){
						$atts['class'] .= ' fade-page';
					} else {
						$atts['data-smooth-scroll'] = '';
					}
				}
				
			}
			
			if( isset( $args->has_children ) && $args->has_children && $depth > 0 ){
				$atts['class'] = 'dropdown-toggle dropdown-item';
			}

			// Allow filtering of the $atts array before using it.
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

			// Build a string of html containing all the atts for the item.
			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			/**
			 * START appending the internal item contents to the output.
			 */
			$item_output = isset( $args->before ) ? $args->before : '';
			
			if( $this->doing_mega_menu && 1 == $depth ){
				
				$width_class  = ( $item->description == 'full-width-column' ) ? 'col-12 mega-menu-fw' : 'col-lg col-md-4';
				$h5_link      = ( strpos( $atts['href'], '#' ) === false ) ? '<a' . $attributes . '>' : false;
				$image_output = ( $menu_image ) ? '<img src="'. esc_url( $menu_image ) .'" alt="Image" />' : false;
				$link_close   = ( $h5_link ) ? '</a>' : false;
				
				$item_output .= '<div class="'. $width_class .' mb-3 mb-lg-0">'. $h5_link . $image_output . $link_close . '<h5>' . $h5_link;
				                        
			} elseif( $this->doing_list_menu && 1 == $depth ){
				
				$item_output .= '<a' . $attributes . '>';
				
				if( $depth > 0 && 'None' !== $menu_icon ) {
					$item_output .= tommusrhodus_svg_icons_pluck( $menu_icon, 'icon icon-md bg-primary' );
				}	

				if( $depth > 0 && $menu_image ) {
					$item_output .= '<img src="'. esc_url( $menu_image ) .'" alt="Icon" class="avatar"/>';
				}	
				
				if( $depth > 0 && $item->description ) {
					$item_output .= '
						<div class="text-body ml-3">
                          	<span>'. $item->title .'</span>
                          	<div class="text-small text-muted">'. $item->description .'</div>
                        </div>
                    ';
                    $item->title = '';
				}
			
			} else {
			
				if( isset( $args->has_children ) && $args->has_children && $depth > 0 ){
					$item_output .= '<div class="dropdown">';
				}
				
				$item_output .= '<a' . $attributes . '>';
				
				if( $depth > 0 && 'None' !== $menu_icon ) {
					$item_output .= tommusrhodus_svg_icons_pluck( $menu_icon, 'icon icon-md bg-primary');
				}
				
				if( isset( $args->has_children ) && $args->has_children && $depth > 0 ){
					$item_output .= '<span>';
				}
			
			}

			/** This filter is documented in wp-includes/post-template.php */
			$title = apply_filters( 'the_title', $item->title, $item->ID );

			/**
			 * Filters a menu item's title.
			 *
			 * @since WP 4.4.0
			 *
			 * @param string   $title The menu item's title.
			 * @param WP_Post  $item  The current menu item.
			 * @param stdClass $args  An object of wp_nav_menu() arguments.
			 * @param int      $depth Depth of menu item. Used for padding.
			 */
			$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

			// Put the item contents into $output.
			$item_output .= isset( $args->link_before ) ? $args->link_before . $title . $args->link_after : '';
			
			if( $this->doing_mega_menu && 1 == $depth ){
				
				$h5_link = ( strpos( $atts['href'], '#' ) === false ) ? '</a>' : false;
				
				$item_output .= $h5_link . '</h5>';
				
			} else {
			
				if( isset( $args->has_children ) && $args->has_children && $depth > 0 ){
					$item_output .= '</span><svg class="icon bg-dark opacity-20" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M14.4444 8.41358C14.7776 8.2281 15.1875 8.46907 15.1875 8.85048V15.1495C15.1875 15.5309 14.7776 15.7719 14.4444 15.5864L8.78505 12.4369C8.44258 12.2463 8.44258 11.7537 8.78505 11.5631L14.4444 8.41358Z" fill="#212529" />
					</svg>';
				}

				$item_output .= '</a>';
			
			}

			$item_output .= isset( $args->after ) ? $args->after : '';

			/**
			 * END appending the internal item contents to the output.
			 */
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

		}
		
		public function end_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
	
			if( !$this->doing_mega_menu ){
			
				if( 0 == $depth ){
					$output .= '</li>';
				}
				
				if( isset( $args->has_children ) && $args->has_children && $depth > 0 ){
					$output .= '</div>';
				}
			
			}
		
		}

		/**
		 * Traverse elements to create list from elements.
		 *
		 * Display one element if the element doesn't have any children otherwise,
		 * display the element and its children. Will only traverse up to the max
		 * depth and no ignore elements under that depth. It is possible to set the
		 * max depth to include all depths, see walk() method.
		 *
		 * This method should not be called directly, use the walk() method instead.
		 *
		 * @since WP 2.5.0
		 *
		 * @see Walker::start_lvl()
		 *
		 * @param object $element           Data object.
		 * @param array  $children_elements List of elements to continue traversing (passed by reference).
		 * @param int    $max_depth         Max depth to traverse.
		 * @param int    $depth             Depth of current element.
		 * @param array  $args              An array of arguments.
		 * @param string $output            Used to append additional content (passed by reference).
		 */
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			if ( ! $element ) {
				return; }
			$id_field = $this->db_fields['id'];
			// Display this element.
			if ( is_object( $args[0] ) ) {
				$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] ); }
			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}

		/**
		 * Wraps the passed text in a screen reader only class.
		 *
		 * @since 4.0.0
		 *
		 * @param string $text the string of text to be wrapped in a screen reader class.
		 * @return string      the string wrapped in a span with the class.
		 */
		private function wrap_for_screen_reader( $text = '' ) {
			if ( $text ) {
				$text = '<span class="sr-only">' . $text . '</span>';
			}
			return $text;
		}

	}
}