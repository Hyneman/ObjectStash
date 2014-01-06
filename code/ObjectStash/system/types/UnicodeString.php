<?php
////
//////
//////   O B J E C T  S T A S H
////// ==========================
////// Copyright © 2013 by Silent Byte.
//////
////// Author: Rico Beti (rico.beti@silentbyte.com)
////// Module: system.types.UnicodeString.php
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


namespace system\types
{
	if(defined("OBJECT_STASH_AUTHORIZED") === false)
		exit("UNAUTHORIZED ACCESS.");

	use BadMethodCallException;
	use Countable;
	use InvalidArgumentException;
	use OutOfBoundsException;

	/**
	 * This class provides methods to perform extended operations on strings.
	 * In contrast to PHP's string functions, this class ensures that functions, arguments
	 * and return values are consistent across the board. It is also capable of working
	 * correctly with Unicode characters as it represents the string entirely in UTF-16.
	 * The data is stored immutable, so every manipulation causes a new string object
	 * to be created that can be further used.
	 */
	final class UnicodeString implements Countable
	{
		#region ...Constants...


		/**
		 * Specifies the internal used encoding (UTF-16).
		 * @var string
		 */
		const INTERNAL_ENCODING = "UTF-16";

		/**
		 * Specifies the encoding of strings when data is returned from methods.
		 * @var string
		 */
		const REPRESENTATIONAL_ENCODING = "UTF-8";


		#end region
		#region ...Member Variables...


		/**
		 * Stores the string value of the object.
		 * @var string
		 */
		private $value;

		/**
		 * Specifies the internal encoding (UTF-16).
		 * This is a member field because it might be subject to change if characters
		 * need to be supported that cannot be stored with UTF-16.
		 * @var string
		 */
		private $encoding;

		/**
		 * Holds the length of the string in characters.
		 * @var integer
		 */
		private $length;


		#end region
		#region ...Constructor...


		/**
		 * Creates the object based on an initial value. An optional encoding
		 * may be specified to declare the encoding of the given string.
		 * Internally, this data will be encoded into an UTF-16 representation.
		 *
		 * @param string $string Initial value of the string object.
		 * @param string $encoding Optional encoding of the given string.
		 */
		public function __construct($string = "", $encoding = UnicodeString::REPRESENTATIONAL_ENCODING)
		{
			$this->encoding = self::INTERNAL_ENCODING;
			if($encoding === null)
				$this->value = mb_convert_encoding($string, $this->encoding);
			else
				$this->value = mb_convert_encoding($string, $this->encoding, $encoding);

			$this->length = mb_strlen($this->value, $this->encoding);
		}


		#end region
		#region ...Factory Methods...


		/**
		 * Converts the specified string into a String object.
		 * The string must be encoded with the internal encoding (self::INTERNAL_ENCODING).
		 *
		 * @param string $string String to be converted.
		 * @return UnicodeString Returns a new string object identical to the specified string.
		 */
		private static function fromMultiByte($string)
		{
			return new UnicodeString($string, self::INTERNAL_ENCODING);
		}


		#end region
		#region ...Static Methods...

/*
		/**
		 * Compares the specified string object and tests for equality.
		 *
		 * @param String $first First string object to be compared.
		 * @param String $second Second string object to be compared.
		 *
		 * @return boolean Returns 'true' if the two specified string objects
		 *    are identical, 'false' otherwise.
		 */
		public static function areEquals(UnicodeString $first, UnicodeString $second)
		{
			if($first === null || $second === null)
				return false;

			if($first === $second)
				return true;

			if($first->length !== $second->length)
				return false;

			if($first->value !== $second->value)
				return false;

			return true;
		}

		/**
		 * Checks whether the specified string object is null or empty.
		 *
		 * @param UnicodeString $string String object to be checked.
		 * @return boolean Returns 'true' if the specified string is either null or empty (""), 'false' otherwise.
		 */
		public static function isNullOrEmpty(UnicodeString $string = null)
		{
			if($string === null)
				return true;

			if($string->value === "")
				return true;

			return false;
		}

		/**
		 * Checks whether the specified string object is null or only contains whitespace.
		 *
		 * @param UnicodeString $string String object to be checked.
		 * @return boolean Returns 'true' if the specified string is either null or only contains whitespace, 'false' otherwise.
		 */
		public static function isNullOrWhiteSpace(UnicodeString $string = null)
		{
			if($string == null)
				return true;

			if($string->trim()->value === "")
				return true;

			return false;
		}


		#end region
		#region ...Methods...


		/**
		 * Gets the length of the string in characters.
		 *
		 * @return number Returns the number of characters of the string.
		 */
		public function getLength()
		{
			return $this->length;
		}

		/**
		 * Gets the internal encoding the string class is using.
		 * @return UnicodeString Returns the encoding identifier.
		 */
		public function getEncoding()
		{
			return $this->encoding;
		}

		/**
		 * Determins the character at the specified position.
		 *
		 * @param string $index Index position of the character.
		 * @return UnicodeString Returns a new string object consisting of only one character.
		 */
		public function charAt($index)
		{
			return $this->substring($index, 1);
		}

		/**
		 * Compares the string with the given text.
		 *
		 * @param UnicodeString $string Another string object to compare with.
		 * @return boolean Returns 'true' if both strings are identical, 'false' otherwise.
		 */
		public function equals(UnicodeString $string)
		{
			return self::areEquals($this, $string);
		}

		/**
		 * Checks if the string is empty.
		 *
		 * @return boolean Returns 'true' if the string is empty, 'false' otherwise.
		 */
		public function isEmpty()
		{
			if($this->value === "")
				return true;

			return false;
		}

		/**
		 * Searches the first occurrence of the specified string starting at the given offset.
		 * If the string could not be found, a negative value will be returned.
		 *
		 * @param UnicodeString $string String to search for.
		 * @param integer $offset Offset that determines the start index of the search.
		 *
		 * @throws OutOfBoundsException Will be thrown if the offset is not within the range of the string.
		 * @return integer Returns the index of the first occurrence of the string.
		 */
		public function indexOf(UnicodeString $string, $offset = 0)
		{
			if($offset < 0 || $offset > $this->length)
				throw new OutOfBoundsException("The specified offset is not within the range of the string.");

			$position = mb_strpos($this->value, $string->value, $offset, $this->encoding);
			if($position === false)
				return -1;

			return $position;
		}

		/**
		 * Converts the string into lowercase.
		 *
		 * @return UnicodeString Returns a new string object representing the string in lowercase.
		 */
		public function toLower()
		{
			return self::fromMultiByte(mb_strtolower($this->value, $this->encoding));
		}

		/**
		 * Converts the string into uppercase.
		 *
		 * @return UnicodeString Returns a new string object representing the string in uppercase.
		 */
		public function toUpper()
		{
			return self::fromMultiByte(mb_strtoupper($this->value, $this->encoding));
		}

		/**
		 * Extracts a substring in the range of 'start' and 'length'.
		 * If no length is specified, the range is set from 'start' to the end of the string.
		 *
		 * @param integer $start Start position of the range.
		 * @param integer $length Number of characters to return.
		 *
		 * @throws InvalidArgumentException Will be thrown if either 'start' or 'length' is negative.
		 * @throws OutOfBoundsException Will be thrown if the specified range is (partially) outside the range of the string.
		 *
		 * @return UnicodeString Returns a new string object representing the substring..
		 */
		public function substring($start, $length = null)
		{
			if($length === null)
				$length = $this->length - $start;

			if($start < 0 || $length < 0)
				throw new InvalidArgumentException("The specified start and length parameters must not be negative.");

			if($start + $length > $this->length)
				throw new OutOfBoundsException("The specified string selection is out of bounds.");

			return new UnicodeString(mb_substr($this->value, $start, $length,
				$this->encoding), $this->encoding);
		}

		/**
		 * Extracts the specified number of characters from the left side of the string.
		 *
		 * @param integer $length Number of characters.
		 * @return UnicodeString Returns a new string object that is 'length' characters long.
		 */
		public function left($length)
		{
			if($length < 0)
				$length = 0;
			else if($length > $this->length)
				$length = $this->length;

			return $this->substring(0, $length);
		}

		/**
		 * Extracts the specified number of characters from the right side of the string.
		 *
		 * @param integer $length Number of characters.
		 * @return UnicodeString Returns a new string object that is 'length' characters long.
		 */
		public function right($length)
		{
			if($length < 0)
				$length = 0;
			else if($length > $this->length)
				$length = $this->length;

			return $this->substring($this->length - $length, $length);
		}

		/**
		 * Removes whitespace on both sides of the string.
		 *
		 * @return UnicodeString Returns the string with all whitespace on both sides removed.
		 */
		public function trim()
		{
			// Native trim() function can be used here because it ignores null characters.
			return self::fromMultiByte(trim($this->value));
		}

		/**
		 * Removes whitespace on the left side of the string.
		 *
		 * @return UnicodeString Returns the string with all whitespace on the left side removed.
		 */
		public function trimLeft()
		{
			// Native ltrim() function can be used here because it ignores null characters.
			return self::fromMultiByte(ltrim($this->value));
		}

		/**
		 * Removes whitespace on the right side of the string.
		 *
		 * @return UnicodeString Returns the string with all whitespace on the right side removed.
		 */
		public function trimRight()
		{
			// Native rtrim() function can be used here because it ignores null characters.
			return self::fromMultiByte(rtrim($this->value));
		}

		/**
		 * This method is used to determine the actual value of the string, depending on the internal encoding.
		 *
		 * @return string Returns a plain string encoded with the internal encoding.
		 */
		public function valueOf()
		{
			return $this->value;
		}

		/**
		 * Encodes the string with the specified encoding.
		 *
		 * @param string $encoding Encoding to be used.
		 * @return string Returns a string representing the text of this object encoded with the specified encoding.
		 */
		public function encode($encoding)
		{
			return mb_convert_encoding($this->value, $encoding, $this->encoding);
		}

		/**
		 * Converts the internally stored string into UTF-8.
		 *
		 * @return string Returns a plain string encoded with UTF-8.
		 */
		public function toString()
		{
			return $this->__toString();
		}


		#end region
		#region ...Countable Implementation...


		/**
		 * This function adds the possibility to use the object with the count() function.
		 *
		 * @return integer Returns the number of characters.
		 */
		public function count()
		{
			return $this->length;
		}


		#end region
		#region ...Magic Methods...


		/**
		 * Used to retrieve properties from the class for easier access to values.
		 * Currently supported properties are:
		 * - length: $this->getLength().
		 * - encoding: $this->getEncoding().
		 *
		 * @param string $name Name of the property to retrieve.
		 *
		 * @throws BadMethodCallException Will be thrown if the specified property is not supported.
		 * @return mixed The return value depends on the specified property.
		 */
		public function __get($name)
		{
			// ASCII function can be used here because PHP doesn't support Unicode identifiers.
			$name = strtolower($name);
			switch($name)
			{
				case "length":
					return $this->getLength();

				case "encoding":
					return $this->getEncoding();
			}

			throw new BadMethodCallException("Undefined property.");
		}

		/**
		 * Encodes the internal string in UTF-8.
		 *
		 * @return string Returns a plain string encoded in UTF-8.
		 */
		public function __toString()
		{
			return $this->encode(self::REPRESENTATIONAL_ENCODING);
		}


		#end region
	} // final class String implements Countable
} // namespace system\types

?>