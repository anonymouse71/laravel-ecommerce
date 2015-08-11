<?php

/**
 *
 */
class ProductsController extends BaseController {

  public function __construct() {
    $this->beforeFilter('csrf' , array('on'=>'post'));
  }

  public function getIndex() {


    /* NOTE :: the categories array is being
    created so that the products index page
    can have access to all the availability
    categories */

     $categories = array();

     foreach (Category::all() as $category) {
        $categories[$category->id] = $category->name;
     }

      return View::make('products.index')
        ->with('products' , Product::all())
        ->with('categories' , $categories);

  }

  public function postCreate() {

    $validator = Validator::make(Input::all() , Product::$rules);

    if($validator->passes()) {
      $product = new Product;
      $product->category_id = Input::get('category_id');
      $product->title = Input::get('title');
      $product->description = Input::get('description');
      $product->price = Input::get('price');

      /* code for saving the image */
      $image = Input::file('image');
      $filename = date('Y-m-d-H-i-s')."-".$image->getClientOriginalName();
      $path = public_path('img/products/' .$filename);
      Image::make($image->getRealPath())->resize(468, 249)->save($path);
      $product->image = 'img/products/'.$filename;
      /*end of code for saving the image */

      $product->save();

      return Redirect::to('admin/products/index')
         ->with('message' , 'Product created');
    }

    return Redirect::to('admin/products/index')
      ->with('message' , 'something went wrong')
      ->withError($validator)
      ->withInput();
  }

  public function postDestroy() {

      $product = Product::find(Input::get('id'));
      if ($product) {
        $product->delete();
        return Redirect::to('admin/products/index')
        ->with('message' , 'product has been deleted');
      }

      return Redirect::to('admin/products/index')
        ->with('message' , 'something went wrong , Please try again');
  }


  /*
    Below function is to toggle availability of a certain product.
  */
  public function postToggleAvailability() {
        $product = Product::find(Input::get('id'));

        if ($product) {
            $product->availability = Input::get('availability');
            $product->save();
            return Redirect::to('admin/products/index')->with('message' , 'Product updated');
        }

        return Redirect::to('admin/products/index')->with('message' , 'Invalid product');
  }

}



?>
