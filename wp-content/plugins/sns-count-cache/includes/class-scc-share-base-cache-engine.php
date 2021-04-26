<?php
/**
class-scc-share-base-cache-engine.php

Description: This class is a data cache engine whitch get and cache data using wp-cron at regular intervals
Author: Daisuke Maruyama
Author URI: https://logicore.cc/
License: GPL2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/*
Copyright (C) 2014 - 2019 Daisuke Maruyama

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * SCC_Share_Base_Cache_Engine
 */
class SCC_Share_Base_Cache_Engine extends SCC_Share_Cache_Engine {

	/**
	 * Prefix of cache ID
	 */
	const DEF_TRANSIENT_PREFIX = 'scc_share_count_';

	/**
	 * Cron name to schedule cache processing
	 */
	const DEF_PRIME_CRON = 'scc_share_basecache_prime';

	/**
	 * Cron name to execute cache processing
	 */
	const DEF_EXECUTE_CRON = 'scc_share_basecache_exec';

	/**
	 * Schedule name for cache processing
	 */
	const DEF_EVENT_SCHEDULE = 'share_base_cache_event';

	/**
	 * Schedule description for cache processing
	 */
 	const DEF_EVENT_DESCRIPTION = '[SCC] Share Base Cache Interval';

	/**
	 * Cache post types
	 *
	 * @var array
	 */
	private $post_types = array( 'post', 'page' );

	/**
	 * Interval cheking and caching target data
	 *
	 * @var integer
	 */
	private $check_interval = 600;

	/**
	 * Number of posts to check at a time
	 *
	 * @var integer
	 */
	private $posts_per_check = 20;

	/**
	 * Offset suffix
	 *
	 * @var string
	 */
	private $offset_suffix = 'base_offset';

	/**
	 * Initialization
	 *
	 * @param array $options Option.
	 * @return void
	 */
	public function initialize( $options = array() ) {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );

		$this->cache_prefix = self::DEF_TRANSIENT_PREFIX;
		$this->prime_cron = self::DEF_PRIME_CRON;
		$this->execute_cron = self::DEF_EXECUTE_CRON;
		$this->event_schedule = self::DEF_EVENT_SCHEDULE;
		$this->event_description = self::DEF_EVENT_DESCRIPTION;

		$this->load_ratio = 0.5;

		if ( isset( $options['delegate'] ) ) {
			$this->delegate = $options['delegate'];
		}

		if ( isset( $options['crawler'] ) ) {
			$this->crawler = $options['crawler'];
		}

		if ( isset( $options['target_sns'] ) ) {
			$this->target_sns = $options['target_sns'];
		}

		if ( isset( $options['check_interval'] ) ) {
			$this->check_interval = $options['check_interval'];
		}

		if ( isset( $options['posts_per_check'] ) ) {
			$this->posts_per_check = $options['posts_per_check'];
		}

		if ( isset( $options['cache_prefix'] ) ) {
			$this->cache_prefix = $options['cache_prefix'];
		}

		if ( isset( $options['prime_cron'] ) ) {
			$this->prime_cron = $options['prime_cron'];
		}

		if ( isset( $options['execute_cron'] ) ) {
			$this->execute_cron = $options['execute_cron'];
		}

		if ( isset( $options['event_schedule'] ) ) {
			$this->event_schedule = $options['event_schedule'];
		}

		if ( isset( $options['event_description'] ) ) {
			$this->event_description = $options['event_description'];
		}

		if ( isset( $options['post_types'] ) ) {
			$this->post_types = $options['post_types'];
		}

		if ( isset( $options['scheme_migration_mode'] ) ) {
			$this->scheme_migration_mode = $options['scheme_migration_mode'];
		}

		if ( isset( $options['scheme_migration_date'] ) ) {
			$this->scheme_migration_date = $options['scheme_migration_date'];
		}

		if ( isset( $options['scheme_migration_exclude_keys'] ) ) {
			$this->scheme_migration_exclude_keys = $options['scheme_migration_exclude_keys'];
		}

		if ( isset( $options['cache_retry'] ) ) {
			$this->cache_retry = $options['cache_retry'];
		}

		if ( isset( $options['retry_limit'] ) ) {
			$this->retry_limit = $options['retry_limit'];
		}

		if ( isset( $options['fault_tolerance_mode'] ) ) {
			$this->fault_tolerance_mode = $options['fault_tolerance_mode'];
		}

		if ( isset( $options['crawl_throttling_mode'] ) ) {
			$this->crawl_throttling_mode = $options['crawl_throttling_mode'];
		}

		add_filter( 'cron_schedules', array( $this, 'schedule_check_interval' ) );
		add_action( $this->prime_cron, array( $this, 'prime_cache' ) );
		add_action( $this->execute_cron, array( $this, 'execute_cache' ), 10, 1 );
	}

	/**
	 * Register event schedule for this engine
	 *
	 * @param array $schedules Schedule.
	 * @return array
	 */
	public function schedule_check_interval( $schedules ) {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );

		$schedules[ $this->event_schedule ] = array(
			'interval' => $this->check_interval,
			'display' => $this->event_description,
		);

		return $schedules;
	}

	/**
	 * Schedule data retrieval and cache processing
	 *
	 * @return void
	 */
	public function prime_cache() {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );

		$next_exec_time = (int) current_time( 'timestamp', 1 ) + $this->check_interval;
		$posts_total = $this->get_posts_total();

		SCC_Logger::log( '[' . __METHOD__ . '] check_interval: ' . $this->check_interval );
		SCC_Logger::log( '[' . __METHOD__ . '] posts_per_check: ' . $this->posts_per_check );
		SCC_Logger::log( '[' . __METHOD__ . '] next_exec_time: ' . $next_exec_time );
		SCC_Logger::log( '[' . __METHOD__ . '] posts_total: ' . $posts_total );

		$option_key = $this->get_cache_key( $this->offset_suffix );

		$posts_offset = get_option( $option_key );

		if ( false === $posts_offset ) {
			$posts_offset = 0;
		}

		SCC_Logger::log( '[' . __METHOD__ . '] posts_offset: ' . $posts_offset );

		wp_schedule_single_event( $next_exec_time, $this->execute_cron, array( (int) $posts_offset ) );

		$posts_offset = $posts_offset + $this->posts_per_check;

		if ( $posts_offset > $posts_total ) {
			$posts_offset = 0;
		}

		update_option( $option_key, $posts_offset );
	}

	/**
	 * Get and cache data of each published post and page
	 *
	 * @param integer $posts_offset Post offset.
	 * @return void
	 */
	public function execute_cache( $posts_offset ) {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );

		SCC_Logger::log( '[' . __METHOD__ . '] posts_offset: ' . $posts_offset );
		SCC_Logger::log( '[' . __METHOD__ . '] check_interval: ' . $this->check_interval );
		SCC_Logger::log( '[' . __METHOD__ . '] posts_per_check: ' . $this->posts_per_check );

		if ( SNS_Count_Cache::OPT_COMMON_CRAWL_THROTTLING_MODE_ON === $this->crawl_throttling_mode ) {

			$options = array();

			if ( $this->scheme_migration_mode ) {
				$options['expected_call_count'] = $this->posts_per_check * 2;
			} else {
				$options['expected_call_count'] = $this->posts_per_check;
			}

			$need_throttle = $this->delegate_order( SCC_Order::ORDER_CHECK_APP_STATUS, $options );

			SCC_Logger::log( '[' . __METHOD__ . '] need throttle: ' );
			SCC_Logger::log( $need_throttle );

			if ( in_array( true, $need_throttle, true ) ) {

				SCC_Logger::log( '[' . __METHOD__ . '] Count retrieval was throttled to prevent or handle API rate limits.' );

				$crons = SCC_WP_Cron::get_scheduled_hook( $this->execute_cron );

				SCC_Logger::log( '[' . __METHOD__ . '] before throttling ' );
				SCC_Logger::log( '[' . __METHOD__ . '] posts_offset: ' . $posts_offset );
				SCC_Logger::log( '[' . __METHOD__ . '] Scheduled: ' );

				foreach ( $crons as $cron ) {
					SCC_Logger::log( '[' . __METHOD__ . '] timestamp: ' . $cron['timestamp'] . ' hook: ' . $this->execute_cron );
					SCC_Logger::log( '[' . __METHOD__ . '] args: ' );
					SCC_Logger::log( $cron['args'] );
				}

				SCC_WP_Cron::clear_scheduled_hook( $this->execute_cron );

				$next_exec_time = (int) current_time( 'timestamp', 1 ) + $this->check_interval;

				wp_schedule_single_event( $next_exec_time, $this->execute_cron, array( (int) $posts_offset ) );

				$next_posts_offset = $posts_offset + $this->posts_per_check;

				$posts_total = $this->get_posts_total();

				if ( $next_posts_offset > $posts_total ) {
					$next_posts_offset = 0;
				}

				$option_key = $this->get_cache_key( $this->offset_suffix );

				update_option( $option_key, $next_posts_offset );

				SCC_Logger::log( '[' . __METHOD__ . '] after throttling ' );
				SCC_Logger::log( '[' . __METHOD__ . '] next_posts_offset: ' . $next_posts_offset );
				SCC_Logger::log( '[' . __METHOD__ . '] Scheduled: ' );

				$crons = SCC_WP_Cron::get_scheduled_hook( $this->execute_cron );

				foreach ( $crons as $cron ) {
					SCC_Logger::log( '[' . __METHOD__ . '] timestamp: ' . $cron['timestamp'] . ' hook: ' . $this->execute_cron );
					SCC_Logger::log( '[' . __METHOD__ . '] args: ' );
					SCC_Logger::log( $cron['args'] );
				}

				return;
			}
		}

		$cache_expiration = $this->get_cache_expiration();

		SCC_Logger::log( '[' . __METHOD__ . '] cache_expiration: ' . $cache_expiration );

		// Cache home url
		if ( 0 === $posts_offset ) {
			$post_id = 'home';

			$transient_id = $this->get_cache_key( $post_id );
			$url = home_url( '/' );
			$title = get_bloginfo( 'name' );

			$options = array(
				'cache_key' => $transient_id,
				'post_id' => $post_id,
				'target_url' => $url,
				'target_sns' => $this->target_sns,
				'target_title' => $title,
				'publish_date' => null,
				'cache_expiration' => $cache_expiration,
			);

			// Primary cache
			$this->cache( $options );

			// Secondary cache
			$this->delegate_order( SCC_Order::ORDER_DO_SECOND_CACHE, $options );
		}

		$query_args = array(
			'post_type' => $this->post_types,
			'post_status' => 'publish',
			'offset' => $posts_offset,
			'posts_per_page' => $this->posts_per_check,
			'no_found_rows' => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		);

		$posts_query = new WP_Query( $query_args );

		if ( $posts_query->have_posts() ) {
			while ( $posts_query->have_posts() ) {
				$posts_query->the_post();

				$post_id = get_the_ID();

				SCC_Logger::log( '[' . __METHOD__ . '] post_id: ' . $post_id );

				$transient_id = $this->get_cache_key( $post_id );

				$url = get_permalink( $post_id );
				$title = get_the_title( $post_id );

				$options = array(
					'cache_key' => $transient_id,
					'post_id' => $post_id,
					'target_url' => $url,
					'target_sns' => $this->target_sns,
					'target_title' => $title,
					'publish_date' => get_the_date( 'Y/m/d' ),
					'cache_expiration' => $cache_expiration,
				);

				// Primary cache
				$this->cache( $options );

				// Secondary cache
				$this->delegate_order( SCC_Order::ORDER_DO_SECOND_CACHE, $options );
			}
		}
		wp_reset_postdata();
	}

	/**
	 * Get and cache data for a given post
	 *
	 * @param integer $post_id Post ID.
	 * @param boolean $second_sync Second sync flag.
	 * @return array
	 */
	public function direct_cache( $post_id, $second_sync = false ) {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );

		$cache_expiration = $this->get_cache_expiration();

		SCC_Logger::log( '[' . __METHOD__ . '] cache_expiration: ' . $cache_expiration );

		$transient_id = $this->get_cache_key( $post_id );

		if ( 'home' !== $post_id ) {
			$url = get_permalink( $post_id );
			$title = get_the_title( $post_id );
			$publish_date = get_the_date( 'Y/m/d', $post_id );
		} else {
			$url = home_url( '/' );
			$title = get_bloginfo( 'name' );
			$publish_date = null;
		}

		$options = array(
			'cache_key' => $transient_id,
			'post_id' => $post_id,
			'target_url' => $url,
			'target_sns' => $this->target_sns,
			'target_title' => $title,
			'publish_date' => $publish_date,
			'cache_expiration' => $cache_expiration,
		);

		// Primary cache
		$result = $this->cache( $options );

		if ( $second_sync ) {
			// Secondary cache
			$this->delegate_order( SCC_Order::ORDER_DO_SECOND_CACHE, $options );
		}

		return $result;
	}

	/**
	 * Get cache expiration based on current number of total post and page
	 *
	 * @return integer
	 */
	protected function get_cache_expiration() {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );

		$posts_total = $this->get_posts_total();

		SCC_Logger::log( '[' . __METHOD__ . '] posts_total: ' . $posts_total );

		return ceil( $posts_total / $this->posts_per_check ) * $this->check_interval * 3;
	}

	/**
	 * Initialize cache
	 *
	 * @return void
	 */
	public function initialize_cache() {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );

		$option_key = $this->get_cache_key( $this->offset_suffix );

		update_option( $option_key, 0 );
	}

	/**
	 * Clear cache
	 *
	 * @return void
	 */
	public function clear_cache() {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );

		$transient_id = $this->get_cache_key( 'home' );

		delete_transient( $transient_id );

		$query_args = array(
			'post_type' => $this->post_types,
			'post_status' => 'publish',
			'nopaging' => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		);

		$posts_query = new WP_Query( $query_args );

		if ( $posts_query->have_posts() ) {
			while ( $posts_query->have_posts() ) {
				$posts_query->the_post();

				$post_id = get_the_ID();

				$transient_id = $this->get_cache_key( $post_id );

				delete_transient( $transient_id );
			}
		}
		wp_reset_postdata();

		$option_key = $this->get_cache_key( $this->offset_suffix );

		delete_option( $option_key );
	}

    /**
	 * Clear cache
	 *
	 * @param integer $post_id Post ID.
	 * @return void
	 */
	public function clear_cache_by_post_id( $post_id ) {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );

		$transient_id = $this->get_cache_key( $post_id );

		delete_transient( $transient_id );
	}

	/**
	 * Get total count of current published post and page
	 *
	 * @return integer
	 */
	private function get_posts_total() {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );

		$query_args = array(
			'post_type' => $this->post_types,
			'post_status' => 'publish',
			'nopaging' => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		);

		$posts_query = new WP_Query( $query_args );

		return $posts_query->found_posts;
	}

	/**
	 * Get cache
	 *
	 * @param array $options Option.
	 * @return array
	 */
	public function get_cache( $options = array() ) {
		$post_id = $options['post_id'];

		$transient_id = $this->get_cache_key( $post_id );

		$sns_counts = array();

		$sns_counts = get_transient( $transient_id );

		if ( false !== $sns_counts ) {
			return $sns_counts;
		} else {
			return $this->delegate_order( SCC_Order::ORDER_GET_SECOND_CACHE, $options );
		}
	}

}

?>
