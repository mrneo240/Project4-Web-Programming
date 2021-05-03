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
