<?php
////
//////
//////   O B J E C T  S T A S H
////// ==========================
////// Copyright © 2013 by Silent Byte.
//////
////// Author: Rico Beti (rico.beti@silentbyte.com)
////// Module: ObjectStash.php
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

define("OBJECT_STASH_AUTHORIZED", true);
define("OBJECT_STASH_TIME", microtime(true));
define("OBJECT_STASH_DIRECTORY", dirname(__FILE__));
define("OBJECT_STASH_SEPARATOR", DIRECTORY_SEPARATOR);

require_once "system/php/AutoLoader.php";

/**
 * Represents the main class of ObjectStash.
 **/
class ObjectStash
{
	#region ...Member Variables...


	/**
	 * Holds the instance of the class loader.
	 * @var system\php\AutoLoader
	 **/
	private $autoLoader;


	#end region
	#region ...Constructor...


	/**
	 * Protected Constructor.
	 * It shall not be allowed for external code to create instances
	 * because this is the main class that can only be instantiated once.
	 **/
	protected function __construct()
	{
		//
	}


	#end region
	#region ...Methods...


	/**
	 * Initializes the environment.
	 **/
	private function initialize()
	{
		$this->autoLoader = new system\php\AutoLoader();
		$this->autoLoader->register();
	}

	/**
	 * Finalizes the execution and prepares to shutdown.
	 **/
	private function finalize()
	{
		$this->autoLoader->unregister();
		$this->autoLoader = null;
	}

	/**
	 * Executes the system to handle requests.
	 **/
	protected function execute()
	{
		try
		{
			// System Processes.
		}
		catch(Exception $e)
		{
			header("HTTP/1.1 500 Internal Server Error");
		}
	}

	/**
	 * Initializes the ObjectStash System.
	 **/
	public static function Main()
	{
		$stash = new ObjectStash();

		$stash->initialize();
		$stash->execute();
		$stash->finalize();
	}


	#end region
} // class ObjectStash


?>
