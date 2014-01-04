<?php
////
//////
//////   O B J E C T  S T A S H
////// ==========================
////// Copyright © 2013 by Silent Byte.
//////
////// Author: Rico Beti (rico.beti@silentbyte.com)
////// Module: system.io.ServerLog.php
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

namespace system\io
{
	if(defined("OBJECT_STASH_AUTHORIZED") === false)
		exit("UNAUTHORIZED ACCESS.");

	use RuntimeException;

	final class ServerLog
	{
		#region ...Member Variables...


		private static $instance;
		private $logger;


		#end region
		#region ...Constructor...


		private function __construct()
		{
			$this->logger = null;
		}


		#end region
		#region ...Methods...


		public static function instance()
		{
			if(self::$instance !== null)
				return self::$instance;

			self::$instance = new ServerLog();
			return self::$instance;
		}

		public function setLogFileName($filename)
		{
			$this->logger = new LogBot($filename);
		}

		/**
		 * Gets the LogBot object that can be used to write log entries.
		 * Note that the returned object changes if the log filename has been changed.
		 *
		 * @throws RuntimeException Will be thrown if the log filename has not been specified.
		 * @return LogBot Returns a LogBot object.
		 */
		public function getLogger()
		{
			if($this->logger === null)
				throw new RuntimeException("Log filename has not been specified.");

			return $this->logger;
		}


		#end region
	} // class ServerLog
} // namespace system\io


?>