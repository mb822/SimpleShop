<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<div style="    background-color: black;
    padding: 100px 100px 200px 100px;">
<div style="margin: 60px 20%;">   <iframe src="https://editor.p5js.org/mikesomething/embed/EcC5Xvswv" frameborder="0"  style="width: 1000px; height:500px;"></iframe>
<h1 style="font-size: 1.2rem;padding-left: 100px;
    font-size: 1.2rem;
    background-color: #ad717c;
    width: 1000px;
    padding-bottom: 3px; ">Click above to start game - Move spaceship using mouse - Shoot enemies using spacebar</h1>

</div></div>

                <div id="useless" >
                        <a href="draw.php" style='margin-left:45%; color:<?php printf( "#%06X\n", mt_rand( 0, 0xffffff )); ?>'>do you like to draw?</a>
                </div>

<?php require_once(__DIR__ . "/partials/flash.php");  
