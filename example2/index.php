
<!DOCTYPE html>
<html lang="en" ng-app="kitchensink">
<head>
    <meta charset="utf-8">

    <title>Free drawing | Fabric.js Demos</title>
    <style>
        pre { margin-left: 15px !important }
    </style>

    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="fabric/dist/fabric.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.6/angular.min.js"></script>
</head>
<body>


<div id="bd-wrapper" ng-controller="CanvasControls">
    <h2><span>Fabric.js demos</span> &middot; Free drawing</h2>

    <style>
        #drawing-mode {
            margin-bottom: 10px;
            vertical-align: top;
        }
        #drawing-mode-options {
            display: inline-block;
            vertical-align: top;
            margin-bottom: 10px;
            margin-top: 10px;
            background: #f5f2f0;
            padding: 10px;
        }
        label {
            display: inline-block; width: 130px;
        }
        .info {
            display: inline-block;
            width: 25px;
            background: #ffc;
        }
        #bd-wrapper {
            min-width: 1500px;
        }
    </style>

    <canvas id="c" width="500" height="500" style="border:1px solid #aaa"></canvas>

    <div style="display: inline-block; margin-left: 10px">
        <button id="drawing-mode" class="btn btn-info">Mode forme</button><br>
        <button id="clear-canvas" class="btn btn-info">Clear</button><br>

        <div id="drawing-mode-options">
            <label for="drawing-mode-selector">Mode:</label>
            <select id="drawing-mode-selector">
                <option>Pencil</option>
                <option>Gomme</option>
                <option>Circle</option>
                <option>Spray</option>
            </select><br>

            <label for="drawing-line-width">Line width:</label>
            <span class="info">30</span><input type="range" value="30" min="0" max="150" id="drawing-line-width"><br>

            <label for="drawing-color">Line color:</label>
            <input type="color" value="#005E7A" id="drawing-color"><br>
        </div>

        <div id="forme-mode-options" style="display:none">
            <input type="button" value="Carré" id="square"/>
            <input type="button" value="Cercle" id="circle"/>
            <input type="button" value="Triangle" id="triangle"/>
            <input type="button" value="remove" id="Remove"/>
        </div>
    </div>

    <script>
        (function() {
            var $ = function(id){return document.getElementById(id)};

            var canvas = this.__canvas = new fabric.Canvas('c', {
                isDrawingMode: true
            });

            fabric.Object.prototype.transparentCorners = false;

            var drawingModeEl = $('drawing-mode'),
                drawingOptionsEl = $('drawing-mode-options'),
                formeOptionsEl = $('forme-mode-options'),
                drawingColorEl = $('drawing-color'),
                drawingLineWidthEl = $('drawing-line-width'),
                clearEl = $('clear-canvas'),
                drawingModeSelector = $('drawing-mode-selector');

            clearEl.onclick = function() { canvas.clear() };

            drawingModeEl.onclick = function() {
                canvas.isDrawingMode = !canvas.isDrawingMode;
                if (canvas.isDrawingMode) {
                    drawingModeEl.innerHTML = 'Mode forme';
                    drawingOptionsEl.style.display = '';
                    formeOptionsEl.style.display = 'none';
                }
                else {
                    drawingModeEl.innerHTML = 'Mode dessin';
                    drawingOptionsEl.style.display = 'none';
                    formeOptionsEl.style.display = '';
                }
            };

            drawingModeSelector.onchange = function() {
                var input_color = jQuery('#drawing-color');
                var label_color = jQuery("label[for='drawing-color']");
                var save_color = jQuery('#save_color');


                if(this.value === 'Gomme'){
                    label_color.hide();
                    input_color.attr('type', 'hidden');
                    jQuery('#drawing-mode-options').append("<input id='save_color' type='hidden' name='save_color' value='" + input_color.val() + "' />");
                    input_color.val('#ffffff');
                }
                else {
                    if(save_color != undefined){
                        input_color.val(save_color.val());
                        save_color.remove();
                    }
                    label_color.show();
                    input_color.attr('type', 'color');
                    canvas.freeDrawingBrush = new fabric[this.value + 'Brush'](canvas);
                }

                if (canvas.freeDrawingBrush) {
                    canvas.freeDrawingBrush.color = drawingColorEl.value;
                    canvas.freeDrawingBrush.width = parseInt(drawingLineWidthEl.value, 10) || 1;
                }
            };

            drawingColorEl.onchange = function() {
                canvas.freeDrawingBrush.color = this.value;
            };
            drawingLineWidthEl.onchange = function() {
                canvas.freeDrawingBrush.width = parseInt(this.value, 10) || 1;
                this.previousSibling.innerHTML = this.value;
            };

            if (canvas.freeDrawingBrush) {
                canvas.freeDrawingBrush.color = drawingColorEl.value;
                canvas.freeDrawingBrush.width = parseInt(drawingLineWidthEl.value, 10) || 1;
                if(drawingModeSelector.value == 'gomme'){
                    canvas.freeDrawingBrush.hasBorders = false;
                    canvas.freeDrawingBrush.hasControls = false;
                    canvas.freeDrawingBrush.hasRotatingPoint = false;
                }
            }

            jQuery('#square').click(function(){
                var square = new fabric.Rect({
                    width: 20, height: 20, fill: drawingColorEl.value, left: 100, top: 100
                });
                canvas.add(square);
            });

            jQuery('#circle').click(function(){
                var circle = new fabric.Circle({
                    radius : 20, fill: drawingColorEl.value, left: 100, top: 100
                });
                canvas.add(circle);
            });

            jQuery('#triangle').click(function(){
                var triangle = new fabric.Triangle({
                    width: 20, height: 20, fill: drawingColorEl.value, left: 100, top: 100
                });
                canvas.add(triangle);
            });

            jQuery('#remove').click(function(){
                if(canvas.getActiveObject() != undefined)
                    canvas.getActiveObject().remove();

                if(canvas.getActiveGroup() != undefined)
                    canvas.getActiveGroup().forEachObject(function(o){ canvas.remove(o) });
            })
        })();
    </script>

</div>

<script>
    (function() {
        fabric.util.addListener(fabric.window, 'load', function() {
            var canvas = this.__canvas || this.canvas,
                canvases = this.__canvases || this.canvases;

            canvas && canvas.calcOffset && canvas.calcOffset();

            if (canvases && canvases.length) {
                for (var i = 0, len = canvases.length; i < len; i++) {
                    canvases[i].calcOffset();
                }
            }
        });
    })();
</script>


</body>
</html>


<?php /* TODO : en mode forme on peut déplacer les tracés de la gomme */