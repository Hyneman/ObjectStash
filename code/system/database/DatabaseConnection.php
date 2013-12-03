<?php
////
//////
//////   O B J E C T  S T A S H
////// ==========================
////// Copyright © 2013 by Silent Byte.
//////
////// Author: Rico Beti (rico.beti@silentbyte.com)
////// Module: system.database.DatabaseConnection.php
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

namespace system\database
{
	if(defined("OBJECT_STASH_AUTHORIZED") === false)
		exit("UNAUTHORIZED ACCESS.");

	use mysqli;
	use system\database\DatabaseException;
	use system\security\SecureHash;

	class DatabaseConnection
	{
		#region ...Member Variables...


		private $mysqli;


		#end region
		#region ...Constructor...


		public function __construct($host, $user, $password)
		{
			$this->mysqli = new mysqli($host, $user, $password);
			if($this->mysqli->connect_error !== null)
			{
				throw new DatabaseException("Database connection could not be established.",
					$this->mysqli->connect_error);
			}
		}


		#end region
		#region ...Methods...


		public function select($database)
		{
			if($this->mysqli->select_db($database) === false)
			{
				throw new RuntimeException("The specified database could not be selected.",
					$this->mysqli->error);
			}
		}

		public function close()
		{
			$this->mysqli->close();
		}

		public function createUser($login, $password)
		{
			$secureHash = new SecureHash();

			$salt = $secureHash->generate();
			$access = $secureHash->generate();
			$hash = $secureHash->compute($password . $salt);

			$statement = $this->mysqli->prepare(
				"INSERT INTO `users` (login, password, salt, access)"
					. " " . "VALUES (?, ?, ?, ?);");

			$statement->bind_param("ssss",
				$login,
				$hash,
				$salt,
				$access);
			
			$statement->execute();
		}

		public function createObject($container, $object, $mime, $tags, $reference)
		{
			$statement = $this->mysqli->prepare(
				"INSERT INTO `objects` (container_id, name, mime, tags, reference)"
					. " " . "VALUES ((SELECT id FROM `containers` WHERE name=?), ?, ?, ?, ?);");

			if($this->mysqli->error)
				die($this->mysqli->error);

			$statement->bind_param("sssss",
				$container,
				$object,
				$mime,
				$tags,
				$reference);
			
			$statement->execute();
		}


		#end region
	} // class DatabaseConnection
} // namespace system\database


?>