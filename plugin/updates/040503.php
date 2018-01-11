<?php

	/**
	 * Очистка старых настроек
	 *
	 * @since 4.5.3
	 */
	class SocialLockerUpdate040503 extends Factory000_Update {

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

				delete_post_meta($locker->ID, 'opanda_facebook_like_tooltip_text');
				delete_post_meta($locker->ID, 'opanda_vk_like_requireSharing');
			}
		}
	}