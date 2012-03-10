<?php
include 'lib/phpQuery-onefile.php';
/**
 * 
 * This class allows you to get data from any site.
 * The data are taken from defined locations in the DOM structure.
 * Data points are defined using the phpquery notation  - similar to the selectors used in JQuery library.
 * This class can fetch data in three different modes by:
 *  - scanning a single page
 *  - scanning a "from->to" range of pages matching defined URL schema
 *  - scanning a list of URLs retrieved from a PHP array  
 * 
 * @example scrap single page
 * $scrap = new Scraper();
 * $scrap->setBaseUrl('http://page.to.scrap/index.html');
 * $scrap->addDataTarget('title', '#product h1');
 * $data = $scrap->process();
 * 
 * @example scrap range of pages
 * $scrap = new Scraper();
 * $scrap->setBaseUrl('http://example.url.com/details.html?id=##TOKEN##');
 * $scrap->addRangeScanRule(151598039, 151598042, '##TOKEN##');
 * $scrap->addDataTarget('name', '#head1 .title h1');
 * $data = $scrap->process();
 *
 * @example scrap list of custom urls
 * $scrap = new Scraper();
 * $myUrls = array('http://site.ccm/ulr1/', 'http://site.ccm/ulr2/', 'http://site.ccm/ulr3/'); 
 * $scrap->addListScanRule($myUrls);
 * $scrap->addDataTarget('title', '#content .ogloszenie_item h1');
 * $scrap->addDataTarget('image', '#content .ogloszenie_thumb a');
 * $scrap->addDataTarget('price', '#content .ogloszenie_item:contains(\'Cena:\')');
 * $data = $scrap->process();
 * 
 * Downloaded data is returned as the array.
 * You can do whatever you want with the data;) 
 *
 * @package Scraper
 * @see This class  uses phpquery library  
 * @link http://code.google.com/p/phpquery/
 * 
 * @author JLukasiewicz jlukasie at gmail
 *
 */
class Scraper 
{
	/**
	 * 
	 * base url to range/single -scan  
	 * @var string
	 */
	private $baseUrl = '';
	
	/**
	 * 
	 * scan rule
	 * Can be 'range' or 'list' type
	 * @var array
	 */
	private $scanRule = array();

	/**
	 * 
	 * Data points in phpquery notation
	 * @var array
	 */
	private $dataTargets = array();
	
	
	public function __construct() 
	{
		
	}
	
	/**
	 * 
	 *  baseUrl setter
	 * @param string $url
	 * @throws Exception
	 */
	public function setBaseUrl($url)
	{
		if (empty($url))
		{
			throw new Exception('Value not specified: url', 1);
		}
		
		$this->baseUrl = $url;
	}

	/**
	 * 
	 * scanRule setter
	 * @param string $type
	 * @param mixed $value
	 * @throws Exception
	 */
	private function setScanRule($type, $value)
	{
		
		if (empty($type) || empty($value))
		{
			throw new Exception('Value not specified: type or value', 1);
		}

		$this->scanRule[$type] = $value; 
	}
	
	
	/**
	 * 
	 * add range rule
	 * @param int $min
	 * @param int $max
	 * @param string $token
	 * @throws Exception
	 */
	public function addRangeScanRule($min, $max, $token)
	{
		if (empty($min) || empty($max))
		{
			throw new Exception('Value not specified: min or max', 1);
		}
		
		$this->setScanRule('range', array('min' => $min, 'max' => $max, 'token' => $token) );
	}
	
	/**
	 * 
	 * add list scan rule
	 * @param array $list
	 * @throws Exception
	 */
	public function addListScanRule($list)
	{
		if (empty($list) || !is_array($list))
		{
			throw new Exception('address list is not specified', 1);
		}		
		
		$this->setScanRule('list', $list);
	}
	
	/**
	 * 
	 * add data point
	 * @param string $name
	 * @param string $selector
	 * @throws Exception
	 */
	public function addDataTarget($name, $selector)
	{
		if (empty($name) || empty($selector))
		{
			throw new Exception('Value not specified: name or selector', 1);
		}
		
		$this->dataTargets[$name] = $selector;
	}
	
	/**
	 * 
	 * perform scan
	 */
	public function process()
	{
		$data = array();
		
		$urls = $this->getUrlsToScan();
		
		foreach ($urls as $url)
		{
			if(!($input = @file_get_contents($url)))
			{
				continue;
			} 
			phpQuery::newDocumentFileHTML($url);
			unset($scrap);
			foreach ($this->dataTargets as $name => $selector)
			{
				$scrap[$name] = pq($selector)->html();	
			}
			if(!empty($scrap))
			{
				$data[] = $scrap;
			}
		}
		
		return $data;
	}
	
	
	/**
	 * 
	 * construct url list to scan
	 * @throws Exception
	 */
	private function getUrlsToScan()
	{
	
		$urls = array();
		
		if (!empty($this->scanRule))
		{
			if(!empty($this->scanRule['range']))
			{
				if (empty($this->baseUrl))
				{
					throw new Exception('baseUrl not specified', 2);
				}
				if(!empty($this->scanRule['range']['min']) && !empty($this->scanRule['range']['max']))
				{
					for($i = $this->scanRule['range']['min']; $i <= $this->scanRule['range']['max']; $i++)
					{
						$urls[] = str_replace($this->scanRule['range']['token'], $i, $this->baseUrl);
					}
				} 
				else 
				{
					throw new Exception('scanRule invalid format', 3);
				}
			}
			elseif (!empty($this->scanRule['list']))
			{
				$urls = $this->scanRule['list'];
			}
		}
		else
		{
			$urls = array($this->baseUrl);
		}
		return $urls;
	}
}