<?php

namespace Ofcold\IPRegion;

use InvalidArgumentException;

/**
 * Class Region
 *
 * @link  https://ofcold.com
 * @link  https://ofcold.com/license
 *
 * @author  Ofcold <support@ofcold.com>
 * @author  Olivia Fu <olivia@ofcold.com>
 * @author  Bill Li <bill.li@ofcold.com>
 *
 * @package  Ofcold\IPRegion\Region
 *
 * @copyright  Copyright (c) 2017-2018, Ofcold. All rights reserved.
 */
class Region
{
	/**
	 * The Regiion instance.
	 *
	 * @var \Ofcold\IPRegion\Region
	 */
	protected static $instance;

	/**
	 * Createa an a new Region instance or false.
	 *
	 * @param  string $ip
	 *
	 * @return static::$instance|boolean
	 */
	public static function make(string $ip)
	{
		if ( ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) )
		{
			return false;
		}

		return static::$instance ?: new static($ip);
	}

	/**
	 * The country name.
	 *
	 * @var string
	 */
	protected $country;

	/**
	 * The national shorthand code.
	 *
	 * @var string
	 */
	protected $countryCode;

	/**
	 * The country province.
	 *
	 * @var string
	 */
	protected $province;

	/**
	 * The country province code.
	 *
	 * @var integer
	 */
	protected $provinceCode;

	/**
	 * The country city.
	 *
	 * @var string
	 */
	protected $city;

	/**
	 * The country city code.
	 *
	 * @var int
	 */
	protected $cityCode;

	/**
	 * The country county.
	 *
	 * @var string
	 */
	protected $county;

	/**
	 * The user use carrier.
	 *
	 * @var string
	 */
	protected $carrier;

	/**
	 * Createa an a new Region instance.
	 *
	 * Build an instance of all settings and fill in values.
	 *
	 * @param  string $ip
	 *
	 * @return void
	 */
	public function __construct(string $ip)
	{
		$property = [
			'country'		=> 'country',
			'countryCode'	=> 'country_id',
			'province'		=> 'region',
			'provinceCode'	=> 'region_id',
			'city'			=> 'city',
			'cityCode'		=> 'city_id',
			'county'		=> 'county',
			'carrier'		=> 'isp'
		];

		try {

			$results = json_decode(file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip={$ip}"), true);

			foreach ( $results['data'] as $key => $value )
			{
				$mehtod = 'set' . ucfirst(array_flip($property)[$key] ?? '');

				if ( $value && in_array($key, $property) && method_exists($this, $mehtod) )
				{
					$this->$mehtod($value);
				}
			}

		}
		catch (InvalidArgumentException $e) {
			throw $e;
		}

	}

	public function getCountry() : string
	{
		return $this->country;
	}

	public function setCountry(string $country)
	{
		$this->country = $country;

		return $this;
	}

	public function getCountryCode()
	{
		return $this->countryCode;
	}

	public function setCountryCode($countryCode)
	{
		$this->countryCode = $countryCode;

		return $this;
	}

	public function getProvince() : string
	{
		return $this->province;
	}

	public function setProvince(string $province)
	{
		$this->province = $province;

		return $this;
	}

	public function getProvinceCode()
	{
		return $this->provinceCode;
	}

	public function setProvinceCode($provinceCode)
	{
		$this->provinceCode = $provinceCode;

		return $this;
	}

	public function getCity() : string
	{
		return $this->city;
	}

	public function setCity(string $city)
	{
		$this->city = $city;

		return $this;
	}

	public function getCityCode() : string
	{
		return $this->cityCode;
	}

	public function setCityCode($cityCode)
	{
		$this->cityCode = $cityCode;

		return $this;
	}

	public function getCounty() : string
	{
		return $this->county;
	}

	public function setCounty(string $county = '')
	{
		$this->county = $county;

		return $this;
	}

	public function getCarrier() : string
	{
		return $this->carrier;
	}

	public function setCarrier(string $carrier)
	{
		$this->carrier = $carrier;

		return $this;
	}

	public function area() : string
	{
		return "{$this->getCountry()} {$this->getProvince()} {$this->getCity()} {$this->getCarrier()}";
	}

	public function __toString() : string
	{
		return $this->area();
	}

	/**
	 * Get the instance as an array.
	 *
	 * @return  array
	 */
	public function toArray() : array
	{
		return [
			'country'		=> $this->getCountry(),
			'country_code'	=> $this->getCountryCode(),
			'province'		=> $this->getProvince(),
			'province_code'	=> $this->getProvinceCode(),
			'city'			=> $this->getCity(),
			'city_code'		=> $this->getCityCode(),
			'county'		=> $this->getCounty(),
			'carrier'		=> $this->getCarrier(),
		];
	}
}