<canvas id="canvas" width="400" height="400">
</canvas>
<canvas id="c2" width="400" height="400"></canvas>

<script>
    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");
    ctx.beginPath();
    ctx.rect(5, 5, 300, 250);
    ctx.stroke();
    ctx.arc(150, 150, 100, 0, Math.PI, false);
    ctx.stroke();

    canvas.addEventListener("click", function (){
        var data = ctx.getImageData(0, 0, canvas.width, canvas.height);

        console.log(data);
        console.log(JSON.stringify(data));

        var c2 = document.getElementById("c2");
        var ctx2 = c2.getContext("2d");
        ctx2.putImageData(data, 0, 0);
    }, false);
</script>