<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Scraper demo</title>
</head>
<body>    
<?php 

include 'scraper.php';

/**
 * 
 * This class allows you to get info from Blogo Gallery pages.
 * Data points are defined using the phpquery notation  - similar to the selectors used in JQuery library.
 */
class BlogoGallery
{
    private $galleries = array();

    public function __construct($url){
        if(!empty($url))        $this->scanGalleries($url);
	}
    
    public function getGalleries(){
        return $this->galleries;
    }
    
    public function getThumbsByGallery($link){
        if(empty($link))
            return array();

        $s = new Scraper();
        $s->setBaseUrl($link);
        $s->addDataTarget('thumbs', 'div.gallery div.thumbs');
        
        $data = $s->process();
        
        $ret = array();
        if(count($data) > 0){
            $info = explode('>', $data[0]['thumbs']); 
            $id = 0;
            foreach ($info as $row){   
                phpQuery::newDocument($row);
                $img = pq('img')->attr('src');
                if(!empty($img))
                    $ret[] = array( 'id'        =>    'thumb_'.$id++,
                                    'thumbimg'  =>    $img,
                                    'baseimg'    =>    pq('img')->attr('data-origsrc'),
                                    'bigimg'    =>    pq('img')->attr('data-bigsrc'),
                                    'title'     =>    pq('img')->attr('alt') );
            }
        }
        
        return $ret;
    }
    
    
    private function scanGalleries($url){
        $s = new Scraper();
        $s->setBaseUrl($url);
        $s->addDataTarget('galleryInfo', 'ul.gallerie-correlate');
        $data = $s->process();
        $ret = array();
        if(count($data) > 0){
            $info = explode('</li>', $data[0]['galleryInfo']);  
            $id = 0;        
            foreach ($info as $row){
                phpQuery::newDocument($row);
                $img = pq('div a img')->attr('src');
                if(!empty($img))
                    $ret[] = array( 'id'    =>    'gallery_'.$id++,
                                    'img'   =>    $img,
                                    'link'  =>    pq('div a')->attr('href'),
                                    'title' =>    pq('span a')->html() );
            }
        }
        $this->galleries = $ret;
    }
    
}; 

/*
 * Examples
 */


/* comment this line with // to enable example section 
 // Example 1
 // Scan single page and grab data
try 
{   
	$scrap = new Scraper();
	
	//set url to scan
	$scrap->setBaseUrl('test.html');
	
	//definition of points where data are 
	$scrap->addDataTarget('title', '#product h1');
	$scrap->addDataTarget('category', '#product #category');
	$scrap->addDataTarget('description', '#product #description');
	$scrap->addDataTarget('price', '#product #price');
	
	//run scan
	$data = $scrap->process();
	
	//show results
	var_dump($data);

}
catch (Exception $e)
{
	echo $e->getMessage();
}
//*/


///* comment this line with // to enable example section
 // Example 2
 // Scan base url with range id's
   
try 
{   
/*	$scrap = new Scraper();
	
	//set base url with token named ##TOKEN##
//	$scrap->setBaseUrl('http://szukaj.pl.mobile.eu/pojazdy/details.html?id=##TOKEN##');
	$scrap->setBaseUrl('http://www.autoblog.it/gallerie/');
	
	//Set the scan range for the token
	//$scrap->addRangeScanRule(151598039, 151598042, '##TOKEN##');
	
	//definition of points where data are 
	$scrap->addDataTarget('name', '.navigation h2');
	//$scrap->addDataTarget('price', '#buyerpricegross');
	$scrap->addDataTarget('image', 'ul.gallerie-correlate li');
	
	
	//run
	$data = $scrap->process();
	
	//output
	foreach ($data as $row)
	{
		echo "<strong>Name:</strong> " . $row['name'] . "<br />";
		
		echo $row['image'] . "<br />";
		echo "<br /> <hr /><br />";		
	}*/
	//var_dump($data);

//    $gal = new BlogoGallery('http://www.mobileblog.it/gallerie/');
//    $gal = new BlogoGallery('http://www.calcioblog.it/gallerie/');
//    $gal = new BlogoGallery('http://www.softblog.it/gallerie/');
//    $gal = new BlogoGallery('http://www.autoblog.it/gallerie/');
//    $gal = new BlogoGallery('http://www.cineblog.it/gallerie/');
    $gal = new BlogoGallery('http://www.tvblog.it/gallerie/');
    
    $i = 0;
    foreach($gal->getGalleries() as $gallery){
        echo '<img src="'.$gallery['img'].'" title="'.$gallery['title'].'" />';
        echo '&nbsp;&nbsp';
        
        $thumbs = $gal->getThumbsByGallery($gallery['link']);
        foreach($thumbs as $thumb)
            echo '<img src="'.$thumb['thumbimg'].'" title="'.$thumb['title'].'" />';
        echo '<br><br>';
        if($i++ > 10)
            break;
        
    }
}
catch (Exception $e)
{
	echo $e->getMessage();
}

//*/




/* comment this line with // to enable example section
 // Example 3
 // Scan list of urls
try 
{
	$scrap = new Scraper();
	
	//define urls to scan
	$myUrls = array(
		'http://wlasnorecznie.boo.pl/wlasnorecznie/content/ciasteczka',
		'http://wlasnorecznie.boo.pl/wlasnorecznie/content/ziarenka-kawy',
		'http://wlasnorecznie.boo.pl/wlasnorecznie/content/pacman-3d'
	);
	
	//add urls to scraper
	$scrap->addListScanRule($myUrls);
	
	//define data points
	$scrap->addDataTarget('title', '#content .ogloszenie_item h1');
	$scrap->addDataTarget('image', '#content .ogloszenie_thumb a');
	$scrap->addDataTarget('price', '#content .ogloszenie_item:contains(\'Cena:\')');
	
	//start
	$data = $scrap->process();
	
	//show results
	var_dump($data);
}
catch (Exception $e)
{
	echo $e->getMessage();
}
//*/


?>
</body></html>