<?php
/*
 * File: templates.inc
 * Project: Project 4
 * Author: Hayden Kowalchuk
 * -----
 * Copyright (c) 2021 Hayden Kowalchuk, Hayden Kowalchuk
 * License: BSD 3-clause "New" or "Revised" License, http://www.opensource.org/licenses/BSD-3-Clause
 */

require_once("registration/config.php");
require_once("cart.php");

function sidebar()
{
  $html = <<<HDC
  <!-- wrapper -->
  <div class="menu">
  <h1 style="text-align: center;border-bottom: 1px solid rgb(204, 204, 204);">Menu</h1>
  <a class="btn_v2 options" type="button" href="home.html"><b>Home</b></a>
  <br><br><br>
  <a class="btn_v2 options" type="button" href="choose-city.html"><b>Cities</b></a>
  <br><br><br>
  <a class="btn_v2 options" type="button" href="#"><b>Profile</b></a>
  <br><br><br>
  <a class="btn_v2 options" type="button" href="registration/logout.php"><b>Log Out</b></a>
  </div>
HDC;

  echo $html;
}

function vehicle_card($id, $name, $type, $price, $seats, $transmission, $img)
{
  $html = <<<HDC
          <div class="vehicle-card">
            <form action="atc.html" method="POST" id="veh{$id}">
              <input type="hidden" name="vehID" value="{$id}">
              <img src="{$img}" alt="car1" />
              <br>
              <h2>{$name}</h2>
              <p>Type: <em>{$type}</em></p>
              <p>Price: <em>\${$price}/day</em></p>
              <p>Seats: <em>{$seats}</em></p>
              <p>Transmission: <em>{$transmission}</em></p>
              <p>Air Conditioning: <em>✓</em></p>
              <button class="add-to-cart" type="submit">Reserve</button>
            </form>
          </div>
HDC;

  echo $html;
}

function get_single_car($id)
{
  global $link;
  $arr = array();

  $vehicle = "SELECT id, name, type, price, seats, transmission, img FROM inventory WHERE id={$id}";
  if ($stmt_veh = mysqli_prepare($link, $vehicle)) {
    mysqli_stmt_execute($stmt_veh);
    mysqli_stmt_bind_result($stmt_veh, $id_2, $name, $type, $price, $seats, $transmission, $img);
    mysqli_stmt_fetch($stmt_veh);
    $arr['id'] = $id_2;
    $arr['name'] = $name;
    $arr['type'] = $type;
    $arr['price'] = $price;
    $arr['seats'] = $seats;
    $arr['transmission'] = $transmission;
    $arr['img'] = $img;
    mysqli_stmt_close($stmt_veh);
  } else {
    die("ERROR: " . mysqli_error($link));
  }

  return $arr;
}

function get_cars($city)
{
  global $link;
  $inventory = "SELECT id, available FROM {$city}";

  if ($stmt = mysqli_prepare($link, $inventory)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $available);
    /* fetch available inventory */
    $cars = array();
    while (mysqli_stmt_fetch($stmt)) {
      /* parking above 100 */
      if ($id < 100 && $available > 0)
        array_push($cars, $id);
    }
    mysqli_stmt_close($stmt);
    /* Now output each vehicle card */
    foreach ($cars as $id) {
      $vehicle = get_single_car($id);
      vehicle_card($vehicle['id'], $vehicle['name'], $vehicle['type'], $vehicle['price'], $vehicle['seats'], $vehicle['transmission'], $vehicle['img']);
    }
  }
}

function get_single_cart_item($id, $name, $img, $price)
{
  $html = <<<HDC
  <div class="product">
    <img src="{$img}" alt="product-img-{$id}">
    <div class="vehicle-info">
      <h3 class="vehicle-name">{$name}</h3>
      <h4 class="vehicle-price">\${$price}/day</h4>
      <button class="vehicle-remove" type="submit" name="remove_button" value="{$id}" formaction="viewcart.html">
        <span class="remove">Remove</span>
      </button>
    </div>
  </div><br>
HDC;

  echo $html;
}

function get_cart()
{
  $cost = 0;
  $cart = cart_get();
  foreach ($cart as $key => $item) {
    $vehicle = get_single_car($item['id']);
    $cost += intval($vehicle['price']);
    get_single_cart_item($vehicle['id'], $vehicle['name'], $vehicle['img'], $vehicle['price']);
  }

  return $cost;
}
