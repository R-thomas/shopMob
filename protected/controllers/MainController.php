<?php
	
class MainController extends Controller
{
    public $layout='//layouts/main'; 
    
    public function actionIndex()
    {
        $banner = Banner::model()->findAll();
        $news = News::model()->findAll();
        $topSales = Models::model()->findAll('top = 1');
        $novelty = Models::model()->findAll('novelty = 1');
        $random = Models::randomId();
        echo '<pre>';
            print_r($random);
            echo '</pre>';
                
        $this->render('index',
        array(
            'banner' => $banner,
            'news' => $news,
            'topSales' => $topSales,
            'novelty' => $novelty,
            'random' => $random,
        ));
    }
    
    public function actionGoods()
    {
        phpinfo();
        $this->render('goods');
    }
    
    public function actionCart()
    {
        $this->render('cart');
    }
}