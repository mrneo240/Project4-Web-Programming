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
  echo "<pre>";
  /* Check if found an increment, otherwise add new */
  $found = false;
  foreach($_SESSION['cart'] as $key => $val) {
    if($val['id'] == $id){
      echo "key = $key \{{$val['id']},{$val['num']}\}  \n";
      $found = $key;
    }
  }
  if ($found) {
    /* found, increment */
    $_SESSION['cart'][$found]['num'] = intval($_SESSION['cart'][$found]['num'])+1;
  } else {
    /* not found */
    $el = array("id" => $id, "num" => 1);
    array_push($_SESSION['cart'], $el);
  }
  echo "</pre>";
}

function cart_remove($id)
{
  $key = array_search($id, $_SESSION['cart']);
  array_slice($_SESSION['cart'], $key, 1);
}

function cart_clear()
{
  $_SESSION['cart'] = array();
}
