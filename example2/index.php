
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
        <button id="drawing-mode" class="btn btn-info">Cancel drawing mode</button><br>
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
                drawingColorEl = $('drawing-color'),
                drawingLineWidthEl = $('drawing-line-width'),
                clearEl = $('clear-canvas');

            clearEl.onclick = function() { canvas.clear() };

            drawingModeEl.onclick = function() {
                canvas.isDrawingMode = !canvas.isDrawingMode;
                if (canvas.isDrawingMode) {
                    drawingModeEl.innerHTML = 'Cancel drawing mode';
                    drawingOptionsEl.style.display = '';
                }
                else {
                    drawingModeEl.innerHTML = 'Enter drawing mode';
                    drawingOptionsEl.style.display = 'none';
                }
            };

            if (fabric.PatternBrush) {
                var vLinePatternBrush = new fabric.PatternBrush(canvas);
                vLinePatternBrush.getPatternSrc = function() {

                    var patternCanvas = fabric.document.createElement('canvas');
                    patternCanvas.width = patternCanvas.height = 10;
                    var ctx = patternCanvas.getContext('2d');

                    ctx.strokeStyle = this.color;
                    ctx.lineWidth = 5;
                    ctx.beginPath();
                    ctx.moveTo(0, 5);
                    ctx.lineTo(10, 5);
                    ctx.closePath();
                    ctx.stroke();

                    return patternCanvas;
                };

                var hLinePatternBrush = new fabric.PatternBrush(canvas);
                hLinePatternBrush.getPatternSrc = function() {

                    var patternCanvas = fabric.document.createElement('canvas');
                    patternCanvas.width = patternCanvas.height = 10;
                    var ctx = patternCanvas.getContext('2d');

                    ctx.strokeStyle = this.color;
                    ctx.lineWidth = 5;
                    ctx.beginPath();
                    ctx.moveTo(5, 0);
                    ctx.lineTo(5, 10);
                    ctx.closePath();
                    ctx.stroke();

                    return patternCanvas;
                };

                var squarePatternBrush = new fabric.PatternBrush(canvas);
                squarePatternBrush.getPatternSrc = function() {

                    var squareWidth = 10, squareDistance = 2;

                    var patternCanvas = fabric.document.createElement('canvas');
                    patternCanvas.width = patternCanvas.height = squareWidth + squareDistance;
                    var ctx = patternCanvas.getContext('2d');

                    ctx.fillStyle = this.color;
                    ctx.fillRect(0, 0, squareWidth, squareWidth);

                    return patternCanvas;
                };

                var diamondPatternBrush = new fabric.PatternBrush(canvas);
                diamondPatternBrush.getPatternSrc = function() {

                    var squareWidth = 10, squareDistance = 5;
                    var patternCanvas = fabric.document.createElement('canvas');
                    var rect = new fabric.Rect({
                        width: squareWidth,
                        height: squareWidth,
                        angle: 45,
                        fill: this.color
                    });

                    var canvasWidth = rect.getBoundingRectWidth();

                    patternCanvas.width = patternCanvas.height = canvasWidth + squareDistance;
                    rect.set({ left: canvasWidth / 2, top: canvasWidth / 2 });

                    var ctx = patternCanvas.getContext('2d');
                    rect.render(ctx);

                    return patternCanvas;
                };

                var img = new Image();
                img.src = '../assets/honey_im_subtle.png';

                var texturePatternBrush = new fabric.PatternBrush(canvas);
                texturePatternBrush.source = img;
            }

            $('drawing-mode-selector').onchange = function() {
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
            }
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
