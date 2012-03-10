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

    public function __construct($url = ''){
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
                    $ret[] = array( 'id'        =>    'thumb-'.$id++,
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

?>