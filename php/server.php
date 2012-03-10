<?php

include 'class.blogogallery.php';


$blog_list = array(
        array('name' => 'Attualit&agrave;', 'type' => 'category'),
        array('baseLink' => 'polisblog', 'name' => 'Polis', 'type' => 'blog'),
        array('baseLink' => 'crimeblog', 'name' => 'Crime', 'type' => 'blog'),
        array('name' => 'Motori', 'type' => 'category'),
        array('baseLink' => 'autoblog', 'name' => 'Auto', 'type' => 'blog'),
        array('baseLink' => 'motoblog', 'name' => 'Moto', 'type' => 'blog'),
        array('baseLink' => 'motorsportblog', 'name' => 'Motorsport', 'type' => 'blog'),
        array('name' => 'Cittadini', 'type' => 'category'),
        array('baseLink' => '02blog', 'name' => 'Prefisso 02', 'type' => 'blog'),
        array('baseLink' => '06blog', 'name' => 'Prefisso 06', 'type' => 'blog'),
        array('name' => 'Spettacoli', 'type' => 'category'),
        array('baseLink' => 'blogapuntate', 'name' => 'BlogaPuntate', 'type' => 'blog'),
        array('baseLink' => 'cineblog', 'name' => 'Cinema', 'type' => 'blog'),
        array('baseLink' => 'gossipblog', 'name' => 'Gossip', 'type' => 'blog'),
        array('baseLink' => 'tvblog', 'name' => 'TV', 'type' => 'blog'),
        array('name' => 'Informatica', 'type' => 'category'),
        array('baseLink' => 'blogvoip', 'name' => 'Voip', 'type' => 'blog'),
        array('baseLink' => 'downloadblog', 'name' => 'Download', 'type' => 'blog'),
        array('baseLink' => 'gamesblog', 'name' => 'Games', 'type' => 'blog'),
        array('baseLink' => 'ossblog', 'name' => 'OSs', 'type' => 'blog'),
        array('name' => 'Hi-Tech', 'type' => 'category'),
        array('baseLink' => 'appblog', 'name' => 'App', 'type' => 'blog'),
        array('baseLink' => 'clickblog', 'name' => 'Click', 'type' => 'blog'),
        array('baseLink' => 'melablog', 'name' => 'Mela', 'type' => 'blog'),
        array('baseLink' => 'gadgetblog', 'name' => 'Gadget', 'type' => 'blog'),
        array('baseLink' => 'mobileblog', 'name' => 'Mobile', 'type' => 'blog'),
        array('name' => 'Lifestyle', 'type' => 'category'),
        array('baseLink' => 'bebeblog', 'name' => 'Beb&egrave;', 'type' => 'blog'),
        array('baseLink' => 'benessereblog', 'name' => 'Benessere', 'type' => 'blog'),
        array('baseLink' => 'deluxeblog', 'name' => 'Deluxe', 'type' => 'blog'),
        array('baseLink' => 'pinkblog', 'name' => 'Pink', 'type' => 'blog'),
        array('name' => 'Tempo libero', 'type' => 'category'),
        array('baseLink' => 'betsblog', 'name' => 'Bets', 'type' => 'blog'),
        array('baseLink' => 'gustoblog', 'name' => 'Gusto', 'type' => 'blog'),
        array('baseLink' => 'petsblog', 'name' => 'Pets', 'type' => 'blog'),
        array('baseLink' => 'toysblog', 'name' => 'Toys', 'type' => 'blog'),
        array('baseLink' => 'travelblog', 'name' => 'Travel', 'type' => 'blog'),
        array('name' => 'Sport', 'type' => 'category'),
        array('baseLink' => 'calcioblog', 'name' => 'Calcio', 'type' => 'blog'),
        array('baseLink' => 'outdoorblog', 'name' => 'Outdoor', 'type' => 'blog'),
        array('name' => 'Eros', 'type' => 'category'),
        array('baseLink' => 'softblog', 'name' => 'Soft', 'type' => 'blog'),
        
    );


class BlogoWrapperSrv{
    private $inputParams = null; 

    public function __construct($params){
        $this->inputParams = $params;
	}
    
    
    public function execute(){
        $ret = array();
        
        $action = $this->inputParams['action'];
        
        if(method_exists($this, $action))
            $ret = $this->$action();
        
        return $ret;
    }    
    
    
    //---------------------------------------------------------------------------------------------------------------------------------------//
    protected function getblogs(){
        $ret = array();
        global $blog_list;    
        $i = 1;
        foreach($blog_list as $blog)
                $ret[] = array( 'id'=> $i++, 'href'=> '#blog-'.$blog['baseLink'], 'name' => $blog['name'], 'type' => $blog['type']);
                
        return $ret;
    }

    protected function gallerylist(){
        $ret = array();
        
        $galleryName = $this->inputParams['name'];
        $gal = new BlogoGallery('http://www.'.$galleryName.'.it/gallerie/');

        $i = 0;
        foreach($gal->getGalleries() as $gallery){
            $ret[] = array( 'id'=> $galleryName.'-'.$i++, 
                            'thumb'=> $gallery['img'],
                            'link'=> $gallery['link'],        
                            'label' => $gallery['title']);
            
            if($i > 10)  break;    
        }
        return $ret;
    }
    
    protected function gallerythumbs(){
        $ret = array();
        
        $galleryLink = $this->inputParams['link'];
        $gal = new BlogoGallery();

        $ret = $gal->getThumbsByGallery($galleryLink);
        return $ret;
    }

}



$srv = new BlogoWrapperSrv($_GET);
echo json_encode($srv->execute());

?>