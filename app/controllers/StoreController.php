<?php

/**
 *
 */
class StoreController extends BaseController {

  function __construct() {
    $this->beforeFilter('csrf' , array('on'=>'post'));
  }

  public function getIndex() {
    return View::make('store.index')
      ->with('products' , Product::take(4)->orderBy('created_at' , 'DESC')->get());
  }

  public function getView($id) {
    return View::make('store.view')->with('product' , Product::find($id));
  }

}





 ?>
