<?php
////
//////
//////   O B J E C T  S T A S H
////// ==========================
////// Copyright © 2013 by Silent Byte.
//////
////// Author: Rico Beti (rico.beti@silentbyte.com)
////// Module: system.web.Url.php
////// 
////// Redistribution and use in source and binary forms, with or without
////// modification, are permitted provided that the following conditions are met:
//////     * Redistributions of source code must retain the above copyright
//////       notice, this list of conditions and the following disclaimer.
//////     * Redistributions in binary form must reproduce the above copyright
//////       notice, this list of conditions and the following disclaimer in the
//////       documentation and/or other materials provided with the distribution.
//////     * Neither the name of the organization nor the names of its contributors
//////       may be used to endorse or promote products derived from this software
//////       without specific prior written permission.
////// 
////// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
////// ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
////// WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
////// DISCLAIMED. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
////// INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
////// LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
////// PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
////// LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE
////// OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
////// ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
//////
////

namespace system\web
{
	if(defined("OBJECT_STASH_AUTHORIZED") === false)
		exit("UNAUTHORIZED ACCESS.");

	use InvalidArgumentException;

	class Url
	{
		#region ...Constants...


		const URL_SEPARATOR = "/";


		#end region
		#region ...Member Variables...


		private $url;
		private $components;


		#end region
		#region ...Constructor...


		/**
		 * Default Constructor.
		 **/
		public function __construct($url)
		{
			$this->url = $url;
			$this->components = parse_url($url);

			if($this->components === false)
				throw new InvalidArgumentException("The specified URL is malformed.");
		}


		#end region
		#region ...Methods...


		private function getComponent($component)
		{
			if(isset($this->components[$component]) === true)
				return $this->components[$component];

			return "";
		}

		public function getUrl()
		{
			return $this->url;
		}

		public function getScheme()
		{
			return $this->getComponent("scheme");
		}

		public function getHost()
		{
			return $this->getComponent("host");
		}

		public function getPort()
		{
			return $this->getComponent("port");
		}

		public function getUser()
		{
			return $this->getComponent("user");
		}

		public function getPassword()
		{
			return $this->getComponent("pass");
		}

		public function getPath()
		{
			return $this->getComponent("path");
		}

		public function getQuery()
		{
			return $this->getComponent("query");
		}

		public function getFragment()
		{
			return $this->getComponent("fragment");
		}


		#end region
	} // class Url
} // namespace system\web

?>