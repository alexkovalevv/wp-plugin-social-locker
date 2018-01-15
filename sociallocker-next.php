<?php
	/**
	 * Plugin Name: {comp:sociallocker}
	 * Plugin URI: {comp:purchaseUrl}
	 * Description: {comp:description}
	 * Author: OnePress
	 * Version: 5.0.2
	 * Author URI: http://byonepress.com
	 */
	
	// ---
	// Constatns & Resources
	//
	
	if( defined('SOCIALLOCKER_PLUGIN_ACTIVE') ) {
		return;
	}
	define('SOCIALLOCKER_PLUGIN_ACTIVE', true);
	
	#comp remove
	if( !defined('ONP_LOCAL_ADDONS') ) {
		define('ONP_LOCAL_ADDONS', true);
	}
	
	// the following constants are used to debug features of diffrent builds
	// on developer machines before compiling the plugin
	
	// build: free, premium, ultimate
	if( !defined('BUILD_TYPE') ) {
		define('BUILD_TYPE', 'premium');
	}

	// language: en_US, ru_RU
	if( !defined('LANG_TYPE') ) {
		define('LANG_TYPE', 'ru_RU');
	}
	
	// license: free, paid
	if( !defined('LICENSE_TYPE') ) {
		define('LICENSE_TYPE', 'paid');
	}
	
	if( !defined('ONP_DEBUG_NETWORK_DISABLED') ) {
		
		define('ONP_DEBUG_NETWORK_DISABLED', false);
		define('ONP_DEBUG_CHECK_UPDATES', false);
	}
	
	if( !defined('ONP_DEBUG_TRIAL_EXPIRES') ) {
		
		define('ONP_DEBUG_TRIAL_EXPIRES', false);
		define('ONP_DEBUG_SHOW_BINDING_MESSAGE', false);
		define('ONP_DEBUG_SHOW_STYLEROLLER_MESSAGE', false);
		define('ONP_DEBUG_SL_OFFER_PREMIUM', false);
		
		// review, subscribe, premium
		define('ONP_SL_ACHIEVEMENT_ACTION', false);
		define('ONP_SL_ACHIEVEMENT_VALUE', false);
		
		// skip trial
		define('ONP_SL_DEBUG_SKIP_TRIAL', false);
	}
	#endcomp
	
	define('SOCIALLOCKER_DIR', dirname(__FILE__));
	define('SOCIALLOCKER_URL', plugins_url(null, __FILE__));
	
	#comp remove
	// the compiler library provides a set of functions like onp_build and onp_license
	// to check how the plugin work for diffrent builds on developer machines
	
	require(SOCIALLOCKER_DIR . '/bizpanda/libs/onepress/compiler/boot.php');
	#endcomp
	
	// ---
	// BizPanda Framework
	//
	
	// inits bizpanda and its items
	require(SOCIALLOCKER_DIR . '/bizpanda/connect.php');
	define('SOCIALLOCKER_BIZPANDA_VERSION', 126);
	
	/**
	 * Fires when the BizPanda connected.
	 */
	function onp_sl_init_bizpanda($activationHook = false)
	{
		/**
		 * Displays a note about that it's requited to update other plugins.
		 */
		if( !$activationHook && !bizpanda_validate(SOCIALLOCKER_BIZPANDA_VERSION, 'Social Locker') ) {
			return;
		}

		load_plugin_textdomain('sociallocker', false, dirname(plugin_basename(__FILE__)) . '/langs');

		// enabling features the plugin requires
		
		BizPanda::enableFeature('lockers');
		BizPanda::enableFeature('terms');
		BizPanda::enableFeature('social');
		
		if( !onp_build('free') ) {
			BizPanda::enableFeature('linkedin');
			BizPanda::enableFeature('sociallocker-premium');
		}
		
		// creating the plugin object
		
		global $sociallocker;
		
		if( onp_lang('ru_RU') ) {
			if( onp_build('ultimate', 'offline') ) {
				$sociallocker = new Factory000_Plugin(__FILE__, array(
					'name' => 'sociallocker-rus',
					'title' => __('Social Locker', 'bizpanda'),
					'version' => '5.0.2',
					'assembly' => BUILD_TYPE,
					'lang' => LANG_TYPE,
					'api' => 'http://api.sociallocker.ru/1.1/',
					'premium' => 'https://sociallocker.ru/download/#sociallocker-purchase-anchor',
					'styleroller' => 'https://sociallocker.ru/styleroller',
					'support' => 'https://sociallocker.ru/create-ticket/',
					'account' => 'https://accounts.sociallocker.ru',
					'updates' => SOCIALLOCKER_DIR . '/plugin/updates/',
					'tracker' => /*@var:tracker*/
						'0900124461779baebd4e030b813535ac'/*@*/,
					'childPlugins' => array('bizpanda')
				));
			} else {
				$sociallocker = new Factory000_Plugin(__FILE__, array(
					'name' => 'sociallocker-rus',
					'title' => __('Social Locker', 'bizpanda'),
					'version' => '5.0.2',
					'assembly' => BUILD_TYPE,
					'lang' => LANG_TYPE,
					'api' => 'http://api.sociallocker.ru/1.1/',
					'premium' => 'https://sociallocker.ru/download/#sociallocker-purchase-anchor',
					'styleroller' => 'http://sociallocker.ru/styleroller',
					'support' => 'https://sociallocker.ru/create-ticket/',
					'account' => 'http://accounts.sociallocker.ru',
					'updates' => SOCIALLOCKER_DIR . '/plugin/updates/',
					'tracker' => /*@var:tracker*/
						'0900124461779baebd4e030b813535ac'/*@*/,
					'childPlugins' => array('bizpanda')
				));
			}
		} else {
			$sociallocker = new Factory000_Plugin(__FILE__, array(
				'name' => 'sociallocker-next',
				'title' => 'Social Locker',
				'version' => '5.0.2',
				'assembly' => BUILD_TYPE,
				'lang' => LANG_TYPE,
				'api' => 'http://api.byonepress.com/1.1/',
				'premium' => 'http://api.byonepress.com/public/1.0/get/?product=sociallocker-next',
				'styleroller' => 'http://sociallocker.org/styleroller',
				'account' => 'http://accounts.byonepress.com/',
				'updates' => SOCIALLOCKER_DIR . '/plugin/updates/',
				'tracker' => /*@var:tracker*/
					'0900124461779baebd4e030b813535ac'/*@*/,
				'childPlugins' => array('bizpanda')
			));
		}
		
		if( onp_build('free') ) {
			if( !onp_lang('ru_RU') ) {
				$sociallocker->options['host'] = 'wordpress.org';
			}
		}
		
		if( onp_build('free') ) {
			BizPanda::registerPlugin($sociallocker, 'sociallocker', 'free');
		}
		if( onp_build('premium') ) {
			BizPanda::registerPlugin($sociallocker, 'sociallocker', 'premium');
		}
		if( onp_build('ultimate', 'offline') ) {
			BizPanda::registerPlugin($sociallocker, 'sociallocker', 'ultimate');
		}
		
		// requires factory modules
		$sociallocker->load(array(
			array('bizpanda/libs/factory/bootstrap', 'factory_bootstrap_000', 'admin'),
			array('bizpanda/libs/factory/notices', 'factory_notices_000', 'admin'),
			array('bizpanda/libs/onepress/api', 'onp_api_000'),
			array('bizpanda/libs/onepress/licensing', 'onp_licensing_000'),
			array('bizpanda/libs/onepress/updates', 'onp_updates_000')
		));

		require(SOCIALLOCKER_DIR . '/plugin/boot.php');

		require(SOCIALLOCKER_DIR . '/panda-items/signin-locker/boot.php');
		require(SOCIALLOCKER_DIR . '/panda-items/social-locker/boot.php');
		
		if( onp_build('ultimate', 'offline') ) {
			if( onp_lang('ru_RU') ) {
				if( file_exists(SOCIALLOCKER_DIR . '/addons/styleroller/styleroller-addon.php') ) {
					$sociallocker->loadAddons(array(
						'styleroller' => SOCIALLOCKER_DIR . '/addons/styleroller/styleroller-addon.php'
					));
				}
			}
		}
		if( onp_build('free', 'premium', 'ultimate', 'offline') ) {
			if( onp_lang('ru_RU') ) {
				if( file_exists(SOCIALLOCKER_DIR . '/addons/buttons-pack/sociallocker-buttons-pack.php') ) {
					$sociallocker->loadAddons(array(
						'sociallocker_buttons_pack' => SOCIALLOCKER_DIR . '/addons/buttons-pack/sociallocker-buttons-pack.php'
					));
				}
				/*if( file_exists(SOCIALLOCKER_DIR . '/addons/bizpanda-popup-mode/bizpanda-popup-mode.php') ) {
					$sociallocker->loadAddons(array(
						'bizpanda_popup_mode' => SOCIALLOCKER_DIR . '/addons/bizpanda-popup-mode/bizpanda-popup-mode.php'
					));
				}*/
			}
		}
	}
	
	add_action('bizpanda_init', 'onp_sl_init_bizpanda');
	
	/**
	 * Activates the plugin.
	 *
	 * TThe activation hook has to be registered before loading the plugin.
	 * The deactivateion hook can be registered in any place (currently in the file plugin.class.php).
	 */
	function onp_sl_activation()
	{
		
		// if the old version of the bizpanda which doesn't contain the function bizpanda_connect has been loaded,
		// ignores activation, the message suggesting to upgrade the plugin will be appear instead
		if( !function_exists('bizpanda_connect') ) {
			return;
		}
		
		// if the bizpanda has been already connected, inits the plugin manually
		if( defined('OPANDA_ACTIVE') ) {
			onp_sl_init_bizpanda(true);
		} else bizpanda_connect();
		
		global $sociallocker;
		$sociallocker->activate();
	}
	
	register_activation_hook(__FILE__, 'onp_sl_activation');
	
	/**
	 * Displays a note about that it's requited to update other plugins.
	 */
	if( is_admin() && defined('OPANDA_ACTIVE') ) {
		bizpanda_validate(SOCIALLOCKER_BIZPANDA_VERSION, 'Social Locker');
	}

