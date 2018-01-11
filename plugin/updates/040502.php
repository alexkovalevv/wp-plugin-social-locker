<?php

	/**
	 * Удалена кнопка facebook-like, добавлена кнопка facebook-share
	 *
	 * @since 4.5.2
	 */
	class SocialLockerUpdate040502 extends Factory000_Update {

		public function install()
		{
			$lockers = get_posts(array(
				'post_type' => OPANDA_POST_TYPE,
				'numberposts' => -1
			));

			foreach($lockers as $locker) {
				$itemType = get_post_meta($locker->ID, 'opanda_item', true);
				if( 'email-locker' === $itemType || 'signin-locker' === $itemType ) {
					continue;
				}

				if( onp_build('free') ) {
					$orders_buttons = get_post_meta($locker->ID, 'opanda_buttons_order', true);
					$split_button = explode(',', $orders_buttons);

					if( in_array('facebook-like', $split_button) ) {
						$fb_like_key = array_search('facebook-like', $split_button);
						unset($split_button[$fb_like_key]);
						$split_button[] = 'facebook-share';
					}

					$save_order_format = implode(',', $split_button);

					update_post_meta($locker->ID, 'opanda_buttons_order', $save_order_format);
					update_post_meta($locker->ID, 'opanda_facebook-like_available', 0);
					update_post_meta($locker->ID, 'opanda_facebook-like_available_is_active', 0);
					update_post_meta($locker->ID, 'opanda_facebook-share_available', 1);
					update_post_meta($locker->ID, 'opanda_facebook-share_available_is_active', 1);
				}
			}
		}
	}