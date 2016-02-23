<!--<div style="min-height:100%; width: 100%; display: flex; align-items: center; justify-content: center;">
    <div>
        <img src="../../../images/cfqn.png" class="img-responsive" style="max-height: 100%;"/>
    </div>
</div>-->

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.12.0.min.js"></script>

<div id="item"><img src="../../../images/tel1.jpg" width="350" height="350" /></div>
<div id="show"></div>
<div id="bg"></div>
<ul>
	<li class="tel1"><img src="../../../images/tel1.jpg" width="250" height="250" /></li>
	<li class="tel2"><img src="../../../images/tel2.jpg" width="250" height="250" /></li>
	<li class="tel2"><img src="../../../images/tel2.jpg" width="250" height="250" /></li>
	<li class="tel3"><img src="../../../images/tel3.jpg" width="250" height="250" /></li>
</ul>

<style>
ul li{
    float: left;
}

#bg{
    width: 100%;
    height: 100%;
    background-color: #ccc;
    opacity: 0.8;
    position: absolute;
    top: 0;
    left: 0;
    display: none;
    z-index: 50;
}


#show{
    width: 550px;
    height: 550px;
    position: absolute;
    top: 20%;
    left: 20%;
    display: none;
    z-index: 100;
}

#show>img{
    width: 550px;
    height: 550px;
}
</style>

<script>
    $(document).ready(function(){
    $('.tel1').on('click', function(){
	   $('#item').html('<img src="../../../images/tel1.jpg" width="350" height="350" />').css('opacity', 0).delay(100).animate({
                        opacity: 1
                    }, 300);
	});    
        
	$('.tel2').on('click', function(){
	   $('#item').html('<img src="../../../images/tel2.jpg" width="350" height="350" />').css('opacity', 0).delay(100).animate({
                        opacity: 1
                    }, 300);
	});
    
    $('.tel3').on('click', function(){
	   $('#item').html('<img src="../../../images/tel3.jpg" width="350" height="350" />').css('opacity', 0).delay(100).animate({
                        opacity: 1
                    }, 300);
	});
    
    $('#item').on('click', function(){
        $('#bg').css('display', 'block')
        $a = $('#item').html()
        $('#show').css('display', 'block').html($a).css('opacity', 0).delay(100).animate({
                        opacity: 1
                    }, 300);
        
        
    })
    
    $('#bg').on('click', function(){
        $('#bg').css('display', 'none')
        $('#show').css('display', 'none')
        
    })
});
</script>