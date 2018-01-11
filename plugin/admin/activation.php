<?php
	/**
	 * Contains functions, hooks and classes required for activating the plugin.
	 *
	 * @author Paul Kashtanoff <pavelkashtanoff@gmail.com>
	 * @copyright (c) 2014, OnePress
	 *
	 * @since 4.0.0
	 * @package sociallocker
	 */

	/**
	 * Changes the text of the button which is shown after the success activation of the plugin.
	 *
	 * @since 3.1.0
	 * @return string
	 */
	function sociallocker_license_manager_success_button()
	{
		return __('Learn how to use the plugin <i class="fa fa-lightbulb-o"></i>', 'sociallocker');
	}

	add_action('onp_license_manager_success_button_' . $sociallocker->pluginName, 'sociallocker_license_manager_success_button');

	/**
	 * Returns an URL where we should redirect a user to, after the success activation of the plugin.
	 *
	 * @since 3.1.0
	 * @return string
	 */
	function sociallocker_license_manager_success_redirect()
	{
		return opanda_get_admin_url('how-to-use', array('onp_sl_page' => 'sociallocker'));
	}

	add_action('onp_license_manager_success_redirect_' . $sociallocker->pluginName, 'sociallocker_license_manager_success_redirect');


	/**
	 * The activator class performing all the required actions on activation.
	 *
	 * @see Factory000_Activator
	 * @since 1.0.0
	 */
	class SocialLocker_Activation extends Factory000_Activator {

		/**
		 * Runs activation actions.
		 *
		 * @since 1.0.1
		 */
		public function activate()
		{
			$this->setupLicense();

			$early_activate = get_option('opanda_tracking', false);

			if( onp_build('free') ) {
				if( !$early_activate ) {
					factory_000_set_lazy_redirect(opanda_get_admin_url('how-to-use', array('opanda_page' => 'optinpanda')));
				}
			}
		}

		/**
		 * Setups the license.
		 *
		 * @since 1.0.0
		 */
		protected function setupLicense()
		{

			// sets the default licence
			// the default license is a license that is used when a license key is not activated

			if( onp_build('premium') ) {

				$this->plugin->license->setDefaultLicense(array(
					'Category' => 'free',
					'Build' => 'premium',
					'Title' => 'OnePress Zero License',
					'Description' => __('Please, activate the plugin to get started. Enter a key
                                    you received with the plugin into the form below.', 'sociallocker')
				));
			}

			if( onp_build('ultimate') ) {

				$this->plugin->license->setDefaultLicense(array(
					'Category' => 'free',
					'Build' => 'ultimate',
					'Title' => 'OnePress Zero License',
					'Description' => __('Please, activate the plugin to get started. Enter a key
                                    you received with the plugin into the form below.', 'sociallocker')
				));
			}

			if( onp_build('free') ) {

				$this->plugin->license->setDefaultLicense(array(
					'Category' => 'free',
					'Build' => 'free',
					'Title' => 'OnePress Public License',
					'Description' => __('Public License is a GPLv2 compatible license.
                                    It allows you to change this version of the plugin and to
                                    use the plugin free. Please remember this license 
                                    covers only free edition of the plugin. Premium versions are 
                                    distributed with other type of a license.', 'sociallocker')
				));
			}
		}
	}

	$sociallocker->registerActivation('SocialLocker_Activation');