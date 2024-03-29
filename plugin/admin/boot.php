<?php
	/**
	 * A set of hooks for the admin part of the Social Locker plugin.
	 *
	 * @author Paul Kashtanoff <pavelkashtanoff@gmail.com>
	 * @copyright (c) 2014, OnePress
	 *
	 * @since 4.0.0
	 * @package sociallocker
	 */

	#comp merge
	require(SOCIALLOCKER_DIR . '/plugin/admin/activation.php');
	require(SOCIALLOCKER_DIR . '/plugin/admin/notices.php');

	if( !onp_build('offline') ) {
		require(SOCIALLOCKER_DIR . '/plugin/admin/pages/license-manager.php');
	}

	if( onp_build('free') ) {
		require(SOCIALLOCKER_DIR . '/plugin/admin/pages/premium.php');
	}

	#endcomp

	// ---
	// Assets
	//

	/**
	 * Adds scripts and styles in the admin area.
	 *
	 * @see the 'admin_enqueue_scripts' action
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function sociallocker_icon_admin_assets($hook)
	{

		if( onp_build('free') ) {
			?>
			<style>
				#menu-posts-opanda-item a[href*="premium-sociallocker-next"] {
					color: #43b8e3 !important;
				}
			</style>
			<?php

			return;
		} else {

			if( !onp_license('free') ) {
				return;
			}

			?>
			<style>
				#toplevel_page_license-manager-sociallocker-next div.wp-menu-image,
				#toplevel_page_license-manager-sociallocker-next:hover div.wp-menu-image,
				#toplevel_page_license-manager-sociallocker-next.wp-has-current-submenu div.wp-menu-image {
					background-position: 8px -30px !important;
				}
			</style>
		<?php
		}
	}

	add_action('admin_enqueue_scripts', 'sociallocker_icon_admin_assets');

	// ---
	// Help
	//

	/**
	 * Registers a help section for the Connect Locker.
	 *
	 * @since 1.0.0
	 */
	function sociallocker_register_help($pages)
	{
		global $opanda_help_cats;
		if( !$opanda_help_cats ) {
			$opanda_help_cats = array();
		}

		$items = array(
			array(
				'name' => 'social-locker',
				'title' => __('Social Locker', 'sociallocker'),
				'hollow' => true,
				'items' => array(
					array(
						'name' => 'what-is-social-locker',
						'title' => __('What is it?', 'sociallocker')
					),
					array(
						'name' => 'usage-example-social-locker',
						'title' => __('Quick Start Guide', 'sociallocker')
					)

					/**
					 * array(
					 * 'name' => 'other-notes-social-locker',
					 * 'title' => __('Other Notes', 'sociallocker')
					 * )
					 */
				)
			),
			array(
				'name' => 'signin-locker',
				'title' => __('Sign-In Locker', 'sociallocker'),
				'hollow' => true,
				'items' => array(
					array(
						'name' => 'what-is-signin-locker',
						'title' => __('What is it?', 'sociallocker')
					),
					array(
						'name' => 'usage-example-signin-locker',
						'title' => __('Quick Start Guide', 'sociallocker')
					),
				)
			)
		);

		$items = apply_filters('onp_sl_register_help_pages', $items);

		array_unshift($pages, array(
			'name' => 'sociallocker',
			'title' => __('Plugin: Social Locker', 'sociallocker'),
			'items' => $items
		));

		return $pages;
	}

	add_filter('bizpanda_help_pages', 'sociallocker_register_help');

	/**
	 * Shows the intro page for the plugin Social Locker.
	 *
	 * @since 1.0.0
	 * @param FactoryPages000_AdminPage $manager
	 * @return void
	 */
	function sociallocker_help_page_optinpanda($manager)
	{
		require SOCIALLOCKER_DIR . '/plugin/admin/pages/help/sociallocker.php';
	}

	add_action('bizpanda_help_page_sociallocker', 'sociallocker_help_page_optinpanda');

	// ---
	// Menu
	//

	/**
	 * Changes the menu title if the Social Locker is an only plugin installed from BizPanda.
	 *
	 * @since 1.0.0
	 * @return string A new menu title.
	 */
	function sociallocker_change_menu_title($title)
	{
		if( !BizPanda::isSinglePlugin() ) {
			return $title;
		}

		return __('Social Locker', 'sociallocker');
	}

	add_filter('bizpanda_menu_title', 'sociallocker_change_menu_title');

	/**
	 * Changes the menu icon if the Social Locker is an only plugin installed from BizPanda.
	 *
	 * @since 1.0.0
	 * @return string A new menu title.
	 */
	function sociallocker_change_menu_icon($icon)
	{
		if( !BizPanda::isSinglePlugin() ) {
			return $icon;
		}

		return SOCIALLOCKER_URL . '/plugin/admin/assets/img/menu-icon.png';
	}

	add_filter('bizpanda_menu_icon', 'sociallocker_change_menu_icon');

	/**
	 * Changes the shortcode icon if the Social Locker is an only plugin installed from BizPanda.
	 *
	 * @since 1.0.0
	 * @return string A new menu title.
	 */
	function sociallocker_change_shortcode_icon($icon)
	{
		if( !BizPanda::isSinglePlugin() ) {
			return $icon;
		}

		return SOCIALLOCKER_URL . '/plugin/admin/assets/img/shortcode-icon.png';
	}

	add_filter('bizpanda_shortcode_icon', 'sociallocker_change_shortcode_icon');

	/**
	 * Changes the menu title of the page 'New Item' if the Social Locker is an only plugin installed from BizPanda.
	 *
	 * @since 1.0.0
	 * @return string A new menu title.
	 */
	function sociallocker_change_new_item_menu_title($title)
	{
		if( !BizPanda::isSinglePlugin() ) {
			return $title;
		}

		return __('+ New Locker', 'sociallocker');
	}

	add_filter('factory_menu_title_new-item-opanda', 'sociallocker_change_new_item_menu_title');

	/**
	 * Changes labels of Panda Items if the Social Locker is an only plugin installed from BizPanda.
	 *
	 * @since 4.0.0
	 * @return mixed A set of new labels
	 */
	function sociallocker_change_items_lables($labels)
	{
		if( !BizPanda::isSinglePlugin() ) {
			return $labels;
		}
		$labels['all_items'] = __('All Lockers', 'sociallocker');
		$labels['add_new'] = __('+ New Locker', 'sociallocker');

		return $labels;
	}

	add_filter('bizpanda_items_lables', 'sociallocker_change_items_lables');

	/**
	 * Makes internal page "License Manager" for the Social Locker
	 *
	 * @since 1.0.0
	 * @return bool true
	 */
	function sociallocker_make_internal_license_manager($internal)
	{

		if( onp_build('premium') ) {
			if( onp_license('free') ) {
				return $internal;
			}
		}

		if( BizPanda::isSinglePlugin() ) {
			return $internal;
		}

		return true;
	}

	add_filter('factory_page_is_internal_license-manager-sociallocker-next', 'sociallocker_make_internal_license_manager');

	/**
	 * Returns an URL of page "Go Premium".
	 */
	function onp_sl_get_premium_page_url($url, $name, $campaign = 'na')
	{
		global $sociallocker;
		if( !empty($name) && !in_array($name, array('social-locker', 'signin-locker')) ) {
			return $url;
		}

		if( get_option('onp_sl_skip_trial', false) ) {
			return onp_sl_get_premium_url($campaign);
		} else {
			return admin_url('edit.php?post_type=opanda-item&page=premium-' . $sociallocker->pluginName);
		}
	}

	add_filter('bizpanda_premium_url', 'onp_sl_get_premium_page_url', 10, 3);

	/**
	 * Returns an URL where the user can purchaes the plugin.
	 */
	function onp_sl_get_premium_url($campaign = 'na')
	{
		global $sociallocker;

		return onp_licensing_000_get_purchase_url($sociallocker, $campaign);
	}

	if( onp_build('free') ) {

		/**
		 * Checks whether the trial should be skipped or not.
		 */
		function onp_set_skip_trial_const()
		{

			if( !defined('ONP_SL_DEBUG_SKIP_TRIAL') || !ONP_SL_DEBUG_SKIP_TRIAL ) {
				$value = get_site_transient('onp_sl_skip_trial');
				if( $value ) {
					return;
				}
			}

			$result = wp_remote_get('http://sociallocker.org/skip-trial.json', array('timeout' => 3));
			set_site_transient('onp_sl_skip_trial', 1, 60 * 60 * 24);

			if( is_wp_error($result) || intval($result['response']['code']) !== 200 ) {
				return;
			}
			$json = json_decode($result['body'], true);

			if( isset($json['skip']) && intval($json['skip']) === 1 ) {
				update_option('onp_sl_skip_trial', 1);
			} else {
				update_option('onp_sl_skip_trial', 0);
			}
		}
		// add_action('admin_init', 'onp_set_skip_trial_const');

	}