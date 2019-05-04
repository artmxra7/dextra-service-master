<?php

namespace App\Http\Controllers;

use App\DataTables\ProductBrandDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateProductBrandRequest;
use App\Http\Requests\UpdateProductBrandRequest;
use App\Repositories\ProductBrandRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class ProductBrandController extends AppBaseController
{
    /** @var  ProductBrandRepository */
    private $productBrandRepository;

    public function __construct(ProductBrandRepository $productBrandRepo)
    {
        $this->productBrandRepository = $productBrandRepo;
    }

    /**
     * Display a listing of the ProductBrand.
     *
     * @param ProductBrandDataTable $productBrandDataTable
     * @return Response
     */
    public function index(ProductBrandDataTable $productBrandDataTable)
    {
        return $productBrandDataTable->render('product_brands.index');
    }

    /**
     * Show the form for creating a new ProductBrand.
     *
     * @return Response
     */
    public function create()
    {
        return view('product_brands.create');
    }

    /**
     * Store a newly created ProductBrand in storage.
     *
     * @param CreateProductBrandRequest $request
     *
     * @return Response
     */
    public function store(CreateProductBrandRequest $request)
    {
        $input = $request->all();

        $productBrand = $this->productBrandRepository->create($input);

        Flash::success('Product Brand saved successfully.');

        return redirect(route('productBrands.index'));
    }

    /**
     * Display the specified ProductBrand.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $productBrand = $this->productBrandRepository->findWithoutFail($id);

        if (empty($productBrand)) {
            Flash::error('Product Brand not found');

            return redirect(route('productBrands.index'));
        }

        return view('product_brands.show')->with('productBrand', $productBrand);
    }

    /**
     * Show the form for editing the specified ProductBrand.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $productBrand = $this->productBrandRepository->findWithoutFail($id);

        if (empty($productBrand)) {
            Flash::error('Product Brand not found');

            return redirect(route('productBrands.index'));
        }

        return view('product_brands.edit')->with('productBrand', $productBrand);
    }

    /**
     * Update the specified ProductBrand in storage.
     *
     * @param  int              $id
     * @param UpdateProductBrandRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductBrandRequest $request)
    {
        $productBrand = $this->productBrandRepository->findWithoutFail($id);

        if (empty($productBrand)) {
            Flash::error('Product Brand not found');

            return redirect(route('productBrands.index'));
        }

        $productBrand = $this->productBrandRepository->update($request->all(), $id);

        Flash::success('Product Brand updated successfully.');

        return redirect(route('productBrands.index'));
    }

    /**
     * Remove the specified ProductBrand from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $productBrand = $this->productBrandRepository->findWithoutFail($id);

        if (empty($productBrand)) {
            Flash::error('Product Brand not found');

            return redirect(route('productBrands.index'));
        }

        $this->productBrandRepository->delete($id);

        Flash::success('Product Brand deleted successfully.');

        return redirect(route('productBrands.index'));
    }
}
