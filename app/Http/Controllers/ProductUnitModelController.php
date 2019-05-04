<?php

namespace App\Http\Controllers;

use App\DataTables\ProductUnitModelDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateProductUnitModelRequest;
use App\Http\Requests\UpdateProductUnitModelRequest;
use App\Repositories\ProductUnitModelRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class ProductUnitModelController extends AppBaseController
{
    /** @var  ProductUnitModelRepository */
    private $productUnitModelRepository;

    public function __construct(ProductUnitModelRepository $productUnitModelRepo)
    {
        $this->productUnitModelRepository = $productUnitModelRepo;
    }

    /**
     * Display a listing of the ProductUnitModel.
     *
     * @param ProductUnitModelDataTable $productUnitModelDataTable
     * @return Response
     */
    public function index(ProductUnitModelDataTable $productUnitModelDataTable)
    {
        return $productUnitModelDataTable->render('product_unit_models.index');
    }

    /**
     * Show the form for creating a new ProductUnitModel.
     *
     * @return Response
     */
    public function create()
    {
        return view('product_unit_models.create');
    }

    /**
     * Store a newly created ProductUnitModel in storage.
     *
     * @param CreateProductUnitModelRequest $request
     *
     * @return Response
     */
    public function store(CreateProductUnitModelRequest $request)
    {
        $input = $request->all();

        $productUnitModel = $this->productUnitModelRepository->create($input);

        Flash::success('Product Unit Model saved successfully.');

        return redirect(route('productUnitModels.index'));
    }

    /**
     * Display the specified ProductUnitModel.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $productUnitModel = $this->productUnitModelRepository->findWithoutFail($id);

        if (empty($productUnitModel)) {
            Flash::error('Product Unit Model not found');

            return redirect(route('productUnitModels.index'));
        }

        return view('product_unit_models.show')->with('productUnitModel', $productUnitModel);
    }

    /**
     * Show the form for editing the specified ProductUnitModel.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $productUnitModel = $this->productUnitModelRepository->findWithoutFail($id);

        if (empty($productUnitModel)) {
            Flash::error('Product Unit Model not found');

            return redirect(route('productUnitModels.index'));
        }

        return view('product_unit_models.edit')->with('productUnitModel', $productUnitModel);
    }

    /**
     * Update the specified ProductUnitModel in storage.
     *
     * @param  int              $id
     * @param UpdateProductUnitModelRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductUnitModelRequest $request)
    {
        $productUnitModel = $this->productUnitModelRepository->findWithoutFail($id);

        if (empty($productUnitModel)) {
            Flash::error('Product Unit Model not found');

            return redirect(route('productUnitModels.index'));
        }

        $productUnitModel = $this->productUnitModelRepository->update($request->all(), $id);

        Flash::success('Product Unit Model updated successfully.');

        return redirect(route('productUnitModels.index'));
    }

    /**
     * Remove the specified ProductUnitModel from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $productUnitModel = $this->productUnitModelRepository->findWithoutFail($id);

        if (empty($productUnitModel)) {
            Flash::error('Product Unit Model not found');

            return redirect(route('productUnitModels.index'));
        }

        $this->productUnitModelRepository->delete($id);

        Flash::success('Product Unit Model deleted successfully.');

        return redirect(route('productUnitModels.index'));
    }
}
