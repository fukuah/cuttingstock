<?php
/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 18.05.2019
 * Time: 2:27
 */

use yii\helpers\Html;

$this->title = 'Распил на заказы';
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$sheetObjectJS = "
    var json = '$sheets';
    var sheets = JSON.parse('$sheets');
";

$canvasAdaptiveJS = <<<JS
  $(document).ready(function(){
        var canvas = document.getElementById('responsive-canvas');
	    ctx = canvas.getContext('2d');
        
        // function resize(){   
        //     var canvas = $("#responsive-canvas");
        //     canvas.outerHeight($(window).height() - canvas.offset().top - Math.abs(canvas.outerHeight(true) - canvas.outerHeight()));
        // }
    
        function drawList(ctx, sheet){
            ctx.fillStyle = "BurlyWood";
            ctx.fillRect(10, 10, sheets[0].length*scale, sheets[0].width*scale);
            
            
            sheet.planks.forEach(function(plank) {
                ctx.fillStyle = "LightCyan";
                ctx.fillRect(10 + plank.x*scale, 10 + plank.y*scale, plank.length*scale, plank.width*scale);
                ctx.fillStyle = "Black";
                ctx.strokeRect(10 + plank.x*scale, 10 + plank.y*scale, plank.length*scale, plank.width*scale);
                
                var rightUpX = parseInt(plank.x) + parseInt(plank.length);
                var rightUpY = parseInt(plank.y) + parseInt(plank.width);
                ctx.fillText("#" + plank.orderID + " " + parseInt(plank.length) + "x" + parseInt(plank.width), 10 + plank.x*scale + plank.length*scale/2 - plank.length*scale/3, 10 + plank.y*scale + plank.width*scale/2);
                ctx.fillText("x: " + rightUpX + "  y: " + rightUpY, 10 + plank.x*scale + plank.length*scale/2 - plank.length*scale/3, 10 + plank.y*scale + plank.width*scale/2 + 13);
            });
            
            var offcuts = sheet.offcuts;
            Object.keys(offcuts).forEach(function(key) {
                var LEFT_DOWN = 0, RIGHT_UP = 1, X = 0, Y = 1,
                    width = offcuts[key][RIGHT_UP][Y] - offcuts[key][LEFT_DOWN][Y], 
                    length = offcuts[key][RIGHT_UP][X] - offcuts[key][LEFT_DOWN][X];
                
                console.log(offcuts[key]);
                ctx.fillStyle = "SandyBrown";
                ctx.fillRect(10 + offcuts[key][LEFT_DOWN][X]*scale, 10 + offcuts[key][LEFT_DOWN][Y]*scale, length*scale, width*scale);
                ctx.fillStyle = "Black";
                ctx.strokeRect(10 + offcuts[key][LEFT_DOWN][X]*scale, 10 + offcuts[key][LEFT_DOWN][Y]*scale, length*scale, width*scale);
            });
            // console.log(sheet.offcuts);
            // sheet.offcuts.forEach(function(offcut) {
            //        
            // });
        } 
      
        $('#next-list').click({sheets: sheets, ctx: ctx}, function() {
            var pageInput = $('#page-hidden');
            var pageTextInput = $('#page');
            var page =  parseInt(pageInput.val());
            if (page + 1 < sheets.length) {
                drawList(ctx, sheets[page + 1]);
                pageInput.val(page + 1);
                pageTextInput.val(page + 1)
            }
        });
        
        $('#prev-list').click({sheets: sheets, ctx: ctx}, function() {
            var pageInput = $('#page-hidden');
            var pageTextInput = $('#page');
            var page =  parseInt(pageInput.val());
            if (page - 1 >= 0) {
                drawList(ctx, sheets[page - 1]);
                pageInput.val(page - 1);
                pageTextInput.val(page - 1)
            }
        });
        
	    ctx.lineWidth = 1;
	    ctx.font = "10px Arial";
        
        var primaryX = 10;
        var primaryY = 10;
        
        var scale = 1/4.5;
        ctx.fillStyle = "Ivory";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        drawList(ctx, sheets[0]);
        
        // resize();
        // $(window).on("resize", function(){                      
        //     resize();
        // });
        
        
  });
JS;


$this->registerCSS($canvasAdaptiveCSS);

$this->registerJS($sheetObjectJS, \yii\web\View::POS_END);
$this->registerJS($canvasAdaptiveJS, \yii\web\View::POS_END)
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div>
        <p></p>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <button id="prev-list">&laquoПредыдущий</button>
        </div>
        <div class="col-sm-4 text-center">
            <label aria-label="Лист:">Лист: </label>
            <input id="page" aria-label="Лист" type="text" size="4" value="0"/>
            <input id="page-hidden" aria-label="Лист" type="hidden" value="0"/>
            <button id="go-to-list">Перейти</button>
        </div>
        <div class="col-sm-4 text-right">
            <button id="next-list">Следующий&raquo</button>
        </div>
        <br>
        <div id="'outer" class="col-md-12">
            <canvas width="1140" height="600" id='responsive-canvas'></canvas>
        </div>
    </div>

    <pre>
        <?php print_r($sheets); ?>
    </pre>
    <pre>
        <?php print_r($sheetsRaw); ?>
    </pre>

</div>