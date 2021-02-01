<?php

namespace MyApp\Controllers;

use MyApp\Auth;
use MyApp\Models\Catalog;
use MyApp\Models\Goods;

class CatalogController extends Controller
{
    public function beforeAction(){
       if(isset($_GET['addGood'])){
           Auth::addToBasket($_GET['addGood']);
//           [$uri] = explode("?", $_SERVER['REQUEST_URI']);
//           $this->redirect($uri);
       }
        if(isset($_GET['removeGood'])){
            Auth::removeFromBasket($_GET['removeGood']);
        }
       return true;
    }
    public function actionIndex(){
        $this->renderTemplate("catalog/index.twig",[
            'categories' => Catalog::getCategories(),
            'slider' => Catalog::getGoodsForSlider(5),
            'all' => Catalog::getAllProducts(),
        ]);
//        $this->renderTemplate("goods.twig", [
//           'goods' => Goods::getAll(),
//        ]);
    }
    public function actionCategory($params){
        $categoryId = array_shift($params);
        $this->renderTemplate("catalog/category.twig", [
            "category" => Catalog::getCategoryById($categoryId),
            "goods" => Catalog::getGoodsByCategory($categoryId)
        ]);
    }
    public function actionGood($params){
        [$categoryId, $goodId] = $params;
        $this->renderTemplate("catalog/good.twig", [
            "category" => Catalog::getCategoryById($categoryId),
            "good" => Catalog::getGoodById($goodId)
        ]);
    }
}