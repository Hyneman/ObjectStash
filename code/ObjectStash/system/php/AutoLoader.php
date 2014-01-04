<?php
////
//////
//////   O B J E C T  S T A S H
////// ==========================
////// Copyright © 2013 by Silent Byte.
//////
////// Author: Rico Beti (rico.beti@silentbyte.com)
////// Module: system.php.AutoLoader.php
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

namespace system\php
{
	if(defined("OBJECT_STASH_AUTHORIZED") === false)
		exit("UNAUTHORIZED ACCESS.");

	use RuntimeException;

	/**
	 * Loads classes automatically when they are used the first time.
	 * The AutoLoader class implements lazy class loading with namespace support.
	 **/
	class AutoLoader
	{
		#region ...Member Variables...


		/**
		 * Indicates whether the class loader has registered itself.
		 * @var boolean
		 **/
		private $registered;


		#end region
		#region ...Constructor...


		/**
		 * Default Constructor.
		 **/
		public function __construct()
		{
			$this->registered = false;
		}


		#end region
		#region ...Methods...


		/**
		 * The actual auto-load handler that calls 'require_once' for
		 * each unknown class that needs to be registered.
		 *
		 * @param string $class Name of the class.
		 *
		 * @return boolean Returns 'true' if a file has been included, 'false' otherwise.
		 **/
		protected function autoloadHandler($class)
		{
			// Replace namespace separator with directory separator.
			$filename = str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";
			if(file_exists($filename) === false)
				return false;

			/** @noinspection PhpIncludeInspection */
			require_once($filename);
			return true;
		}

		/**
		 * Registers the class loader.
		 * @throws RuntimeException Will be thrown if the auto-load handler could not be registered.
		 **/
		public function register()
		{
			if($this->registered === true)
				return;

			if(spl_autoload_register([$this, "autoloadHandler"], false) === true)
				$this->registered = true;
			else
				throw new RuntimeException("The auto-load handler could not be registered.");
		}

		/**
		 * Unregisters the class loader.
		 * @throws RuntimeException Will be thrown if the auto-load handler could not be unregistered.
		 **/
		public function unregister()
		{
			if($this->registered === false)
				return;

			if(spl_autoload_unregister([$this, "autoloadHandler"]) === false)
				throw new RuntimeException("The auto-load handler could not be unregistered.");

			$this->registered = false;
		}


		#end region
	} // class AutoLoader
} // namespace system\php

?>