<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController as Controller;
use App\Repositories\ProductRepository;

use App\Models\Product;

class ProductController extends Controller
{

    /** @var  ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepository = $productRepo;
    }

    public function index()
    {
        $product = Product::with('productBrand', 'productUnitModel')->get();
        return $this->sendResponse($product, "success");
    }

    public function getListUnitModel($unit_model)
    {
        $product  = $this->productRepository->getByUnitModel($unit_model);
        return $this->sendResponse($product, "success");
    }

    public function show($id)
    {
        $product = $this->productRepository
          ->with('productBrand', 'productUnitModel')
          ->findWithoutFail($id);

        if (empty($product)) {
            return $this->sendError('Product not found.', 404);
        }

        return $this->sendResponse($product, "success");
    }
}
