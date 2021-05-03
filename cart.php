<?php
/*
 * File: cart.php
 * Project: Project 4
 * Author: Hayden Kowalchuk
 * -----
 * Copyright (c) 2021 Hayden Kowalchuk, Hayden Kowalchuk
 * License: BSD 3-clause "New" or "Revised" License, http://www.opensource.org/licenses/BSD-3-Clause
 */

function cart_add($id)
{
  /* Check if found an increment, otherwise add new */
  $found = false;
  foreach ($_SESSION['cart'] as $key => $val) {
    if ($val['id'] == $id) {
      $found = $key;
      break;
    }
  }
  if ($found !== false) {
    /* found, increment */
    $_SESSION['cart'][$found]['num'] = intval($_SESSION['cart'][$found]['num']) + 1;
  } else {
    /* not found */
    $el = array("id" => $id, "num" => 1);
    array_push($_SESSION['cart'], $el);
  }
}

function cart_remove($id)
{
  /* Check if found an increment, otherwise add new */
  $found = false;
  foreach ($_SESSION['cart'] as $key => $val) {
    if ($val['id'] == $id) {
      $found = $key;
      break;
    }
  }
  if ($found !== false) {
    /* found, remove */
    array_splice($_SESSION['cart'], $found, 1);
  }
}

function cart_clear()
{
  $_SESSION['cart'] = array();
}

function cart_get()
{
  return $_SESSION['cart'];
}

function cart_num()
{
  return count(cart_get());
}

function cart_place_order($total_cost)
{
  global $link;
  $cart_string = serialize($_SESSION['cart']);

  /* Setup location inventory */
  $insert_order = 'INSERT INTO orders (user_id, cart, price, date_start, date_end, city) VALUES (?,?,?,?,?,?)';
  mysqli_select_db($link, DB_DATABASE);
  $stmt_city = mysqli_prepare($link, $insert_order);
  if (!$stmt_city) {
    die("ERROR: Could not prepare order. " . mysqli_error($link));
    mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
  }
  if (!mysqli_stmt_bind_param($stmt_city, 'isiiis', $_SESSION['id'], $cart_string, $total_cost, $_SESSION['reserve_start'], $_SESSION['reserve_end'], $_SESSION['city'])) {
    die("ERROR: Could not bind order. " . mysqli_error($link));
    mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
    return;
  }
  if (!mysqli_stmt_execute($stmt_city)) {
    die("ERROR: Could not add order. " . mysqli_error($link));
    mysqli_query($link, 'DROP DATABASE ' . DB_DATABASE);
  }
  return mysqli_insert_id($link);
}

function order_update_stock($city, $id)
{
  global $link;
  /* Decrement available stock */
  $inv_update = "UPDATE " . $city . " SET available=available-1 WHERE id={$id} AND available>0";
  if ($stmt_up = mysqli_prepare($link, $inv_update)) {
    mysqli_stmt_execute($stmt_up);
    mysqli_stmt_close($stmt_up);
  } else {
    die("ERROR: " . mysqli_error($link));
  }
}
