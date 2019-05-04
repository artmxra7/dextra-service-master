<?php

namespace App\Http\Controllers;

use App\DataTables\ProductDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\ProductRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\ProductUnitModel;
use App\Models\ProductBrand;

class ProductController extends AppBaseController
{
    /** @var  ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepository = $productRepo;
    }

    /**
     * Display a listing of the Product.
     *
     * @param ProductDataTable $productDataTable
     * @return Response
     */
    public function index(ProductDataTable $productDataTable)
    {
        return $productDataTable->render('products.index');
    }

    /**
     * Show the form for creating a new Product.
     *
     * @return Response
     */
    public function create()
    {
        return view('products.create')
                ->with('product_unit_models', ProductUnitModel::pluck('name', 'id'))
                ->with('product_brands', ProductBrand::pluck('name', 'id'))
                ;
    }

    /**
     * Store a newly created Product in storage.
     *
     * @param CreateProductRequest $request
     *
     * @return Response
     */
    public function store(CreateProductRequest $request)
    {
        $req = $request->all();
        $req['slug'] = str_slug($req['title']);
        $photos = [];

        foreach (range(1, 3) as $i) {
            $reqName = 'photo_' . $i;

            if ($request->hasFile($reqName)) {
                $photo = $request->$reqName;
                $extension = $photo->extension();
                $title = $req['slug'] . '_' . $i;
                $path = './attachments/products';
                $filename = $title.'.'.$extension;

                $photo->move($path, $filename);
                $photos[] = $filename;
            }   
        }
        
        $req['photo'] = implode(',', $photos);
        
        $this->productRepository->create($req);
        Flash::success('Product saved successfully.');

        return redirect(route('products.index'));
    }

    /**
     * Display the specified Product.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            Flash::error('Product not found');

            return redirect(route('products.index'));
        }

        return view('products.show')->with('product', $product);
    }

    /**
     * Show the form for editing the specified Product.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            Flash::error('Product not found');
            return redirect(route('products.index'));
        }

        return view('products.edit')
                ->with('product', $product)
                ->with('product_unit_models', ProductUnitModel::pluck('name', 'id'))
                ->with('product_brands', ProductBrand::pluck('name', 'id'));
    }

    /**
     * Update the specified Product in storage.
     *
     * @param  int              $id
     * @param UpdateProductRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductRequest $request)
    {
        $req = $request->all();
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            Flash::error('Product not found');
            return redirect(route('products.index'));
        }

        $photos = [];
        $existingPhotos = explode(',', $product->photo);
        
        foreach (range(1, 3) as $i) {
            $reqName = 'photo_' . $i;

            if ($request->hasFile($reqName)) {
                $photo = $request->$reqName;
                $extension = $photo->extension();
                $title = $product->slug . '_' . $i;
                $path = './attachments/products';
                $filename = $title.'.'.$extension;

                $photo->move($path, $filename);
                $photos[] = $filename;
            } else {
                $photos[] = $existingPhotos[$i - 1];
            }
        }

        $req['photo'] = implode(',', $photos);
        $product = $this->productRepository->update($req, $id);

        Flash::success('Product updated successfully.');
        return redirect(route('products.index'));
    }

    /**
     * Remove the specified Product from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $product = $this->productRepository->findWithoutFail($id);

        if (empty($product)) {
            Flash::error('Product not found');
            return redirect(route('products.index'));
        }

        $photos = explode(',', $product->photo);
        $path = 'attachments/products/';

        foreach ($photos as $photo) {
            $file = $path . $photo;

            if (\File::exists($file)) {
                \File::delete($file);
            }
        }

        $this->productRepository->delete($id);
        Flash::success('Product deleted successfully.');

        return redirect(route('products.index'));
    }
}
