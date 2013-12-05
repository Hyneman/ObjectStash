<?php
////
//////
//////   O B J E C T  S T A S H
////// ==========================
////// Copyright © 2013 by Silent Byte.
//////
////// Author: Rico Beti (rico.beti@silentbyte.com)
////// Module: system.requests.RequestController.php
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

	use system\io\XJson;
	use system\database\DatabaseConnection;
	use system\requests\RequestBroker;

	class RequestController
	{
		#region ...Member Variables...


		private $settings;
		private $broker;
		private $database;


		#end region
		#region ...Constructor...


		public function __construct()
		{
			$this->settings = XJson::fromFile("./application/settings/stash.xjson");
			$this->broker = new RequestBroker($_SERVER);
			$this->database = new DatabaseConnection($this->settings->getDom()->database->host,
				$this->settings->getDom()->database->user,
				$this->settings->getDom()->database->password);

			$this->database->select($this->settings->getDom()->database->connection);
		}


		#end region
		#region ...Methods...

		private function errorUnknownMethod()
		{
			header("HTTP/1.1 400 Bad Request");
		}

		private function handleHead()
		{
			header("HTTP/1.1 501 Not Implemented");
		}

		private function handleGet()
		{
			header("HTTP/1.1 501 Not Implemented");
		}

		private function handlePost()
		{
			header("HTTP/1.1 501 Not Implemented");
		}

		private function handlePut()
		{
			list($container, $object) = explode("/", $this->broker->getUri());

			$login = $this->broker->getHeader("X-OBJECTSTASH-LOGIN");
			$password = $this->broker->getHeader("X-OBJECTSTASH-PASSWORD");

			if($this->database->validCredentials($login, $password) === false)
			{
				header("HTTP/1.1 401 Unauthorized");
				return false;
			}

			if($this->database->objectExists($container, $object) === true)
			{
				header("HTTP/1.1 409 Conflict");
				return false;
			}

			$hash = $this->broker->receiveObject();
			if($hash === false)
			{
				header("HTTP/1.1 409 Conflict");
				return false;
			}

			$this->database->createObject($container, $object, "", "", $hash);
		}

		private function handleDelete()
		{
			header("HTTP/1.1 501 Not Implemented");
		}

		public function handleRequest()
		{
			switch(strtoupper($this->broker->getMethod()))
			{
				case "HEAD":
					$this->handleHead();
					break;

				case "GET":
					$this->handleGet();
					break;

				case "POST":
					$this->handlePost();
					break;

				case "PUT":
					$this->handlePut();
					break;

				case "DELETE":
					$this->handleDelete();
					break;

				default:
					$this->errorUnknownMethod();
					break;
			}
		}


		#end region
	} // class RequestController
} // namespace system\requests


?>