<?php
////
//////
//////   O B J E C T  S T A S H
////// ==========================
////// Copyright © 2013 by Silent Byte.
//////
////// Author: Rico Beti (rico.beti@silentbyte.com)
////// Module: system.requests.RequestBroker.php
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

namespace system\requests
{
	if(defined("OBJECT_STASH_AUTHORIZED") === false)
		exit("UNAUTHORIZED ACCESS.");

	class RequestBroker
	{
		#region ...Member Variables...


		private $request;
		private $uri;
		private $queries;


		#end region
		#region ...Constructor...


		public function __construct($request)
		{
			$this->request = $request;
			$this->uri = self::parseUri($request);
			$this->queries = self::parseQueries($request);
		}


		#end region
		#region ...Methods...

		private static function parseUri($request)
		{
			$uri = $request["REQUEST_URI"];
			$queryIndex = strpos($uri, "?");

			if($queryIndex === false)
				return $uri;

			return substr($uri, 0, $queryIndex);
		}

		private static function parseQueries($request)
		{
			$array = array();
			parse_str($request["REQUEST_URI"], $array);

			return $array;
		}

		public function getUri()
		{
			return $this->uri;
		}

		public function getQuery($query)
		{
			if(isset($this->queries[$query]) === false)
				return "";

			return $this->queries[$query];
		}

		public function getHeader($variable)
		{
			$variable = "HTTP_" . strtoupper(str_replace("-", "_", $variable));
			if(isset($this->request[$variable]) === false)
				return "";

			return $this->request[$variable];
		}


		#end region
	} // class RequestBroker
} // namespace system\requests


?>