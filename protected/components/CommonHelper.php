<?php
class CommonHelper{
    
    public static function getSliderThree1Column($model){
        
        echo '<div id="carousel" class="carousel slide" data-ride="carousel">
              <!--Контент слайдера-->
                <div class="carousel-inner">';  
                    
        $i = 0;
        $j = 10;
        
        echo '<div class="item active">';
        
        foreach ($model as $iter=>$item)
        {    
            $j++;
            $i++;
            
            if($i == 3)
            {
                echo '<div class="col-md-40 col-sm-40 col-xs-40">   ';
                echo '<img src="../../../upload/images/'.$item->photo.'" width="40" height="60" class="img-responsive center-block"/>   ';
                echo '</div>   ';
                echo '</div>   ';
                $i = 5;
                continue;
            }
            
            if($i%3 == 0)
            {
                $j=0;
                echo '<div class="item">';
            }    
            
            echo '<div class="col-md-40 col-sm-40 col-xs-40">   ';
            echo '<img src="../../../upload/images/'.$item->photo_other.'" width="40" height="60" class="img-responsive center-block"/>   ';
            echo '</div>';
            
            
            if($j == 2)
            {
                echo '</div>   ';
            } 
            
        }  
        if($i == 5){
            echo "<div>";
        }
        if($j == 2)
            echo "<div>";
                
                
        echo '</div>
        </div>
        <!--Стрелки влево и вправо-->
        <a href="#carousel" class="left carousel-control" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a href="#carousel" class="right carousel-control" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
        </div>';
    }
    
    public static function getSliderThreeColumn($model)
    {
        
        $main_photo = $model->photo;
        $other_photo = json_decode($model->photo_other);
        
        
        echo '<div id="carousel1" class="carousel slide" data-ride="carousel">
              <!--Контент слайдера-->
              <div class="carousel-inner product_carousel1">
              <div class="item active">
                <div class="col-md-40 col-sm-40 col-xs-40 slider1_item">
                    <img src="../../upload/images/'.$main_photo.'" class="tel1"/>
                </div>
                <script>
                  $(".tel1").on("click", function(){
            	   $("#item").html(\'<img src="../../upload/images/'.$main_photo.'"/>\').css("opacity", 0).delay(100).animate({
                                    opacity: 1
                                }, 300)})
                  </script>
                
                ';
                if (isset($other_photo))
                {
                    $count = count($other_photo);
                    $j = 0;
                    foreach($other_photo as $k=>$item)
                    {
                        if ($k == 0)
                        {
                            echo '<div class="col-md-40 col-sm-40 col-xs-40 slider1_item">
                                <img src="../../upload/images/'.$item.'" class="tel'.($k+2).'"/>
                              </div>
                              <script>
                                  $(".tel'.($k+2).'").on("click", function(){
                  	                  $("#item").html(\'<img src="../../upload/images/'.$item.'"/>\').css("opacity", 0).delay(100).animate({
                                          opacity: 1
                                      }, 300)})
                              </script>
                              ';
                              echo ($count == 1) ?'</div>':'';
                        }
                        
                        else if ($k == 1)
                        {
                            echo '<div class="col-md-40 col-sm-40 col-xs-40 slider1_item">
                                    <img src="../../upload/images/'.$item.'" class="tel'.($k+2).'" />
                                  </div>
                                  <script>
                                      $(".tel'.($k+2).'").on("click", function(){
                      	                  $("#item").html(\'<img src="../../upload/images/'.$item.'"/>\').css("opacity", 0).delay(100).animate({
                                              opacity: 1
                                          }, 300)})
                                  </script>
                                  ';
                              echo ($count == 2) ?'</div>':'';
                              echo ($count > 2) ?'</div>':'';  
                        }
                        else if ($k>1)
                        {
                            $j++;
                            if($j == 1)
                            {
                                echo '<div class="item">
                                        <div class="col-md-40 col-sm-40 col-xs-40 slider1_item">
                                            <img src="../../upload/images/'.$item.'" class="tel'.($k+2).'" />
                                        </div>
                                        
                                        <script>
                                              $(".tel'.($k+2).'").on("click", function(){
                              	                  $("#item").html(\'<img src="../../upload/images/'.$item.'"/>\').css("opacity", 0).delay(100).animate({
                                                      opacity: 1
                                                  }, 300)})
                                          </script>' ;    
                            }
                            else if($j == 2)
                            {
                                echo '<div class="col-md-40 col-sm-40 col-xs-40 slider1_item">
                                            <img src="../../upload/images/'.$item.'" class="tel'.($k+2).'" />
                                        </div>
                                        <script>
                                          $(".tel'.($k+2).'").on("click", function(){
                          	                  $("#item").html(\'<img src="../../upload/images/'.$item.'"/>\').css("opacity", 0).delay(100).animate({
                                                  opacity: 1
                                              }, 300)})
                                      </script>
                                        ';
                            }
                            else if($j == 3)
                            {
                                echo '<div class="col-md-40 col-sm-40 col-xs-40 slider1_item">
                                            <img src="../../upload/images/'.$item.'" class="tel'.($k+2).'" />
                                        </div></div>
                                        
                                        <script>
                                          $(".tel'.($k+2).'").on("click", function(){
                          	                  $("#item").html(\'<img src="../../upload/images/'.$item.'"/>\').css("opacity", 0).delay(100).animate({
                                                  opacity: 1
                                              }, 300)})
                                      </script>';
                                $j = 0;       
                            }
                        }
                        
                            
                              
                    }
                    if(($j == 1)||($j == 2))
                    {
                        echo '</div>';
                    }
                }
                
                echo '
              
              </div>
              <!--Стрелки влево и вправо-->
              <a href="#carousel1" class="left carousel-control" data-slide="prev">
                  <span class="glyphicon glyphicon-chevron-left"></span>
              </a>
              <a href="#carousel1" class="right carousel-control" data-slide="next">
                  <span class="glyphicon glyphicon-chevron-right"></span>
              </a>
              </div>';  
                
    }
    
    
    public static function getSliderFourColumn($slider)
    {      
        $count = count($slider);
        $i = 0;
        if ($count>0)
        {
               
            echo '<div id="carousel2" class="carousel slide" data-ride="carousel">
                  <!--Контент слайдера-->
                  <div class="carousel-inner product_carousel2">';
            
                  
            foreach ($slider as $k=>$item)
            {
                
                if ($k == 0)
                {
                    echo '<div class="item active">
                            <div class="col-md-30 col-sm-30 col-xs-30"><a href="'.Yii::app()->request->baseUrl.'/main/product/'.$item->id.'">
                                <div class="carousel_photo"><img src="../../upload/images/'.$item->photo.'" class="tel1"/></div>
                                <div class="product_accessories_name">'.$item->brandModel->brand.' '.$item->model_name.'</div></a>
                            </div>';
                    if($count == 1)
                    {
                        echo '</div>';
                    }      
                }
                
                
                
                if ($k == 1)
                {
                    echo '<div class="col-md-30 col-sm-30 col-xs-30"><a href="'.Yii::app()->request->baseUrl.'/main/product/'.$item->id.'">
                                <div class="carousel_photo"><img src="../../upload/images/'.$item->photo.'" class="tel1"/></div>
                                <div class="product_accessories_name">'.$item->brandModel->brand.' '.$item->model_name.'</div></a>
                            </div>';
                    if($count == 2)
                    {
                        echo '</div>';
                    }      
                          
                }
                
                
                
                if ($k == 2)
                {
                    echo '<div class="col-md-30 col-sm-30 col-xs-30"><a href="'.Yii::app()->request->baseUrl.'/main/product/'.$item->id.'">
                                <div class="carousel_photo"><img src="../../upload/images/'.$item->photo.'" class="tel1"/></div>
                                <div class="product_accessories_name">'.$item->brandModel->brand.' '.$item->model_name.'</div></a>
                            </div>';
                    if($count == 3)
                    {
                        echo '</div>';
                    }        
                }
                
                if ($k == 3)
                {
                    echo '<div class="col-md-30 col-sm-30 col-xs-30"><a href="'.Yii::app()->request->baseUrl.'/main/product/'.$item->id.'">
                                <div class="carousel_photo"><img src="../../upload/images/'.$item->photo.'" class="tel1"/></div>
                                <div class="product_accessories_name">'.$item->brandModel->brand.' '.$item->model_name.'</div></a>
                            </div></div>';
                }
                
                if ($k > 3)
                {
                    $i++;
                    if($i == 1)
                    {
                        
                        echo '<div class="item">
                                <div class="col-md-30 col-sm-30 col-xs-30"><a href="'.Yii::app()->request->baseUrl.'/main/product/'.$item->id.'">
                                <div class="carousel_photo"><img src="../../upload/images/'.$item->photo.'" class="tel1"/></div>
                                <div class="product_accessories_name">'.$item->brandModel->brand.' '.$item->model_name.'</div></a>
                            </div>';
                    }
                    
                    
                    if($i == 2)
                    {
                        echo '<div class="col-md-30 col-sm-30 col-xs-30"><a href="'.Yii::app()->request->baseUrl.'/main/product/'.$item->id.'">
                                <div class="carousel_photo"><img src="../../upload/images/'.$item->photo.'" class="tel1"/></div>
                                <div class="product_accessories_name">'.$item->brandModel->brand.' '.$item->model_name.'</div></a>
                            </div>';
                    }
                    
                    if($i == 3)
                    {
                        echo '<div class="col-md-30 col-sm-30 col-xs-30"><a href="'.Yii::app()->request->baseUrl.'/main/product/'.$item->id.'">
                                <div class="carousel_photo"><img src="../../upload/images/'.$item->photo.'" class="tel1"/></div>
                                <div class="product_accessories_name">'.$item->brandModel->brand.' '.$item->model_name.'</div></a>
                            </div>';
                    }
                    
                    if($i == 4)
                    {
                        echo '<div class="col-md-30 col-sm-30 col-xs-30"><a href="'.Yii::app()->request->baseUrl.'/main/product/'.$item->id.'">
                                <div class="carousel_photo"><img src="../../upload/images/'.$item->photo.'" class="tel1"/></div>
                                <div class="product_accessories_name">'.$item->brandModel->brand.' '.$item->model_name.'</div></a>
                            </div></div>';
                        $i = 0;
                    }
                }
                
                
            }
            
            if($i == 1 || $i == 2 || $i == 3)
            {
                echo '</div>';
            }        
                    echo '
                  
                  </div>
                  <!--Стрелки влево и вправо-->
                  <a href="#carousel2" class="left carousel-control" data-slide="prev">
                      <span class="glyphicon glyphicon-chevron-left"></span>
                  </a>
                  <a href="#carousel2" class="right carousel-control" data-slide="next">
                      <span class="glyphicon glyphicon-chevron-right"></span>
                  </a>
                  </div>'; 
        }          
                
    }
    
    
    
    
}