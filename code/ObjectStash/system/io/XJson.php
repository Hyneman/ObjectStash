<?php
////
//////
//////   O B J E C T  S T A S H
////// ==========================
////// Copyright © 2013 by Silent Byte.
//////
////// Author: Rico Beti (rico.beti@silentbyte.com)
////// Module: system.io.XJson.php
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

	use InvalidArgumentException;

	/**
	 * Represents the XJson parser that implements the
	 * ability to use comment lines within a JSON document.
	 **/
	class XJson
	{
		#region ...Member Variables...


		/**
		 * Holds the DOM object.
		 **/
		private $dom;


		#end region
		#region ...Constructor...


		/**
		 * Parses the specified XJson string definition.
		 *
		 * @param string $json XJson string to be parsed.
		 * @throws InvalidArgumentException Will be thrown if the JSON is invalid.
		 **/
		private function __construct($json)
		{
			$this->dom = json_decode($json);
			if(json_last_error() !== JSON_ERROR_NONE)
				throw new InvalidArgumentException("XJson could not be parsed.");
		}


		#end region
		#region ...Methods...


		/**
		 * Creates a new XJson object based on the specified string.
		 *
		 * @param string $string XJson string to be parsed.
		 * @return XJson XJson object representing the input.
		 **/
		public static function fromString($string)
		{
			$string = preg_replace("/^\s*\/\/.*$/mS", "", $string);
			if($string === null)
				return null;

			return new XJson($string);
		}

		/**
		 * Creates a new XJson object based on the XJson definition found in the specified file.
		 *
		 * @param string $filename The path to the file to be read and parsed.
		 * @return XJson XJson object representing the input document.
		 **/
		public static function fromFile($filename)
		{
			$content = file_get_contents($filename);
			if($content === false)
				return null;

			return XJson::fromString($content);
		}

		/**
		 * Gets the DOM stored within the object.
		 * @return Object representing the current DOM.
		 **/
		public function getDom()
		{
			return $this->dom;
		}


		#end region
	} // class XJson
} // namespace system\io


?>