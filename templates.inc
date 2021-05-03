<?php

function sidebar(){
  $html = <<<HDC
  <!-- wrapper -->
  <div class="menu">
  <h1 style="text-align: center;border-bottom: 1px solid rgb(204, 204, 204);">Menu</h1>
  <a class="btn_v2 options" type="button" href="home.html"><b>Home</b></a>
  <br><br><br>
  <a class="btn_v2 options" type="button" href="choose-city.html"><b>Cities</b></a>
  <br><br><br>
  <a class="btn_v2 options" type="button" href="choose-city.html"><b>Profile</b></a>
  <br><br><br>
  <a class="btn_v2 options" type="button" href="#"><b>Log Out</b></a>
  </div>
HDC;

  echo $html;
}