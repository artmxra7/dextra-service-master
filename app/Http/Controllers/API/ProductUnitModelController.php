<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController as Controller;
use App\Repositories\ProductUnitModelRepository;

class ProductUnitModelController extends Controller
{

    /** @var  ProductUnitModelRepository */
    private $productRepository;

    public function __construct(ProductUnitModelRepository $productRepo)
    {
        $this->productRepository = $productRepo;
    }

    public function index()
    {
        $product  = $this->productRepository->api();
        return $this->sendResponse($product, "success");
    }

    public function index_name()
    {
        $product  = $this->productRepository->getName();
        return $this->sendResponse($product, "success");
    }
}
