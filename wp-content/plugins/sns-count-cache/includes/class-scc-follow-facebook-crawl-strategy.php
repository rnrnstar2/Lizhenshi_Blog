<?php
/**
class-scc-follow-facebook-crawl-strategy.php

Description: This class is abstract class of a data crawler
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
 * SCC_Follow_Facebook_Crawl_Strategy
 */
class SCC_Follow_Facebook_Crawl_Strategy extends SCC_Crawl_Strategy {

	/**
	 * SNS base URL
	 *
	 * @var string
	 */
	const DEF_BASE_URL = 'https://graph.facebook.com/v3.2/';

	/**
	 * Class constarctor
	 * Hook onto all of the actions and filters needed by the plugin.
	 */
	protected function __construct() {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );

		$this->http_method = 'GET';
	}

	/**
	 * Initialization
	 *
	 * @param array $options Option.
	 * @return void
	 */
	public function initialize( $options = array() ) {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );

		$this->set_parameters( $options );
	}

	/**
	 * Build request header
	 *
	 * @return array
	 */
	public function build_request_header() {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );
		return null;
	}

	/**
	 * Build request body
	 *
	 * @return array
	 */
	public function build_request_body() {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );
		return null;
	}

	/**
	 * Build request URL
	 *
	 * @return string
	 */
	public function build_request_url() {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );

		$url = self::DEF_BASE_URL . $this->parameters['page_id'] . '/insights/page_fans/lifetime' . '?' . http_build_query( $this->query_parameters , '' , '&' );

		return $url;
	}

	/**
	 * Extract response body
	 *
	 * @param array $content Content.
	 * @return array
	 */
	public function extract_response_body( $content ) {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );

		$count = (int) -1;

		if ( isset( $content['body'] ) && empty( $content['error'] ) ) {

			$json = json_decode( $content['body'], true );

			SCC_Logger::log( $json );

			$i = 0;

			while ( $i < 30 ) {
				if ( isset( $json['data'][0]['values'][ $i ]['value'] ) && is_numeric( $json['data'][0]['values'][ $i ]['value'] ) ) {
					$count = (int) $json['data'][0]['values'][ $i ]['value'];
				}
				++$i;
			}
		} else {
			$count = (int) -1;

			if ( isset( $content['error'] ) ) {
				SCC_Logger::log( $content['error'] );
			}
		}

		return $count;
	}

	/**
	 * Extract response header
	 *
	 * @param array $content Content.
	 * @return array
	 */
	public function extract_response_header( $content ) {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );
		return null;
	}

	/**
	 * Set parameter
	 *
	 * @param string $key Key.
	 * @param string $value Value.
	 * @return void
	 */
	public function set_parameter( $key, $value ) {
		if ( 'access_token' === $key ) {
			$this->query_parameters[ $key ] = $value;
		} elseif ( 'client_id' === $key ) {
			$this->parameters[ $key ] = $value;
		} elseif ( 'client_secret' === $key ) {
			$this->parameters[ $key ] = $value;
		} elseif ( 'page_id' === $key ) {
			$this->parameters[ $key ] = $value;
		}
	}

	/**
	 * Check if required paramters are included or not.
	 *
	 * @return boolean
	 */
	public function check_configuration() {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );

		if ( ! empty( $this->parameters['client_id'] ) && ! empty( $this->parameters['client_secret'] ) &&
			! empty( $this->query_parameters['access_token'] ) && ! empty( $this->parameters['page_id'] ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Check if there is rate limiting or not.
	 *
	 * @param string $content Content.
	 * @return boolean
	 */
	public function check_rate_limiting( $content ) {
		SCC_Logger::log( '[' . __METHOD__ . '] (line=' . __LINE__ . ')' );

		return true;
	}

}
