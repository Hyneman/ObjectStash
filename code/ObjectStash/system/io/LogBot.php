<?php
////
//////
//////   O B J E C T  S T A S H
////// ==========================
////// Copyright © 2013 by Silent Byte.
//////
////// Author: Rico Beti (rico.beti@silentbyte.com)
////// Module: system.io.LogBot.php
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

	/**
	 * A simple logger class that can creates plain text log files.
	 **/
	class LogBot
	{
		#region ...Member Variables...


		/**
		 * Path to the log file.
		 **/
		private $filename;

		/**
		 * Handle of the log file.
		 **/
		private $file;

		/**
		 * Output will be flushed immediately if set to 'true'.
		 **/
		private $flush;


		#end region
		#region ...Constructor & Destructor...


		/**
		 * Creates a new LogBot object that writes log messages into the specified file.
		 *
		 * @param string $filename Path to the log file.
		 * @param boolean $flush Ensures that messages are always flushed to disk immediately.
		 *
		 * @throws RuntimeException Will be thrown if the specified file could not be opened.
		 **/
		public function __construct($filename, $flush = true)
		{
			$this->filename = $filename;
			$this->flush = $flush;
			$this->file = fopen($filename, "a");

			if($this->file === false)
				throw new RuntimeException("The specified file '$filename' could not be opened.");
		}

		/**
		 * Destroys the object and releases the file handle.
		 **/
		public function __destruct()
		{
			$this->close();
		}


		#end region
		#region ...Methods...


		/**
		 * Replaces newline characters with a space and reduces
		 * multiple consecutively spaces into one single space.
		 *
		 * @param string $string String to be normalized.
		 * @return string Returns the normalized string.
		 **/
		private function normalizeWhiteSpace($string)
		{
			return preg_replace("/(\\s{2,}|(\r\n|\n|\r)+)/S", " ", $string);
		}

		/**
		 * Writes the specified message directly into the log file.
		 *
		 * @param string $type Type of the log entry.
		 * @param string $message Message to be written.
		 *
		 * @return boolean Returns 'true' on success, 'false' otherwise.
		 **/
		private function raw($type, $message)
		{
			if($this->file === null)
				return false;

			$message = $this->normalizeWhiteSpace($message);
			$message = str_pad($type, 8, " ", STR_PAD_RIGHT)
				. "    " . date("Y-m-d H:i:s")
				. "    " . $message . PHP_EOL;

			$length = strlen($message);
			if(fwrite($this->file, $message) !== $length)
				return false;

			if($this->flush === true)
				fflush($this->file);

			return true;
		}

		/**
		 * Writes the specified error message into the log file.
		 **/
		public function error($message)
		{
			return $this->raw("error", $message);
		}

		/**
		 * Closes the log file and ignores all further requests
		 * to write entries into the log file.
		 **/
		public function close()
		{
			if($this->file === null)
				return;

			fclose($this->file);
			$this->file = null;
		}


		#end region
	} // class LogBot
} // namespace system\io


?>