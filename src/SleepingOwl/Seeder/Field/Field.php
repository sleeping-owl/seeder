<?php namespace SleepingOwl\Seeder\Field;

/**
 * @property string $name
 * @property string $firstName
 * @property string $lastName
 *
 * @property string $citySuffix
 * @property string $streetSuffix
 * @property string $buildingNumber
 * @property string $city
 * @property string $streetName
 * @property string $streetAddress
 * @property string $postcode
 * @property string $address
 * @property string $country
 * @property float  $latitude
 * @property float  $longitude
 *
 * @property string $ean13
 * @property string $ean8
 *
 * @property string $phoneNumber
 *
 * @property string $company
 * @property string $companySuffix
 *
 * @property string $creditCardType
 * @property string $creditCardNumber
 * @property string $creditCardExpirationDate
 * @property string $creditCardExpirationDateString
 * @property string $creditCardDetails
 * @property string $bankAccountNumber
 * @property string $swiftBicNumber
 * @property string $vat
 *
 * @method string anyOf($model)
 * @method string call(\Closure $closure)
 *
 * @property string $word
 * @method string words()
 * @method string sentence()
 * @method string sentences()
 * @method string paragraph()
 * @method string paragraphs()
 * @method string text()
 *
 * @method string realText()
 *
 * @property string $email
 * @property string $safeEmail
 * @property string $freeEmail
 * @property string $companyEmail
 * @property string $freeEmailDomain
 * @property string $safeEmailDomain
 * @property string $userName
 * @property string $domainName
 * @property string $domainWord
 * @property string $tld
 * @property string $url
 * @property string $ipv4
 * @property string $ipv6
 * @property string $internalIpv4
 * @property string $macAddress
 *
 * @property int       $unixTime
 * @property \DateTime $dateTime
 * @property \DateTime $dateTimeAD
 * @property string    $iso8601
 * @property \DateTime $dateTimeThisCentury
 * @property \DateTime $dateTimeThisDecade
 * @property \DateTime $dateTimeThisYear
 * @property \DateTime $dateTimeThisMonth
 * @property string    $amPm
 * @property int       $dayOfMonth
 * @property int       $dayOfWeek
 * @property int       $month
 * @property string    $monthName
 * @property int       $year
 * @property int       $century
 * @property string    $timezone
 * @method string date()
 * @method string time()
 * @method \DateTime dateTimeBetween()
 *
 * @property string $md5
 * @property string $sha1
 * @property string $sha256
 * @property string $locale
 * @property string $countryCode
 * @property string $languageCode
 * @method boolean boolean()
 *
 * @property int    $randomDigit
 * @property int    $randomDigitNotNull
 * @property string $randomLetter
 * @method int randomNumber()
 * @method mixed randomKey()
 * @method int numberBetween()
 * @method float randomFloat()
 * @method string randomElement()
 * @method string numerify()
 * @method string lexify()
 * @method string bothify()
 * @method string toLower()
 * @method string toUpper()
 * @method mixed optional()
 * @method $this unique()
 *
 * @method integer biasedNumberBetween()
 *
 * @property string $userAgent
 * @property string $chrome
 * @property string $firefox
 * @property string $safari
 * @property string $opera
 * @property string $internetExplorer
 *
 * @property string $uuid
 *
 * @property string $mimeType
 * @property string $fileExtension
 *
 * @property string $hexcolor
 * @property string $safeHexColor
 * @property string $rgbcolor
 * @property string $rgbColorAsArray
 * @property string $rgbCssColor
 * @property string $safeColorName
 * @property string $colorName
 */
class Field
{

	/**
	 * @var array
	 */
	protected $chain = [];

	/**
	 * @param $method
	 * @param array $arguments
	 * @return $this
	 */
	public function faker($method, $arguments = [])
	{
		$this->chain[] = [$method, $arguments];
		return $this;
	}

	/**
	 * @param $name
	 * @param $arguments
	 * @return Field
	 */
	function __call($name, $arguments)
	{
		return $this->faker($name, $arguments);
	}

	/**
	 * @param $name
	 * @return Field
	 */
	function __get($name)
	{
		return $this->faker($name);
	}

	/**
	 * @param $faker
	 * @return mixed
	 */
	public function generateNew($faker)
	{
		$object = $faker;
		foreach ($this->chain as list($name, $arguments))
		{
			$object = call_user_func_array([$object, $name], $arguments);
		}
		return $object;
	}

} 