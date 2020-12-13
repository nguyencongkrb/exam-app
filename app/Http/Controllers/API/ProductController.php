<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Repositories\Product\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Product\ProductRepositoryInterface;

class ProductController extends Controller
{
    /**
     * @var ProductRepository
     */
    protected $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->productRepo->getAll();
        return response([ 'product' => ProductResource::collection($products), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:255|unique:products',
            'price' => 'required|regex:/^\d{1,8}(\.\d{1,2})?$/'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $product = $this->productRepo->create($data);

        return response(['product' => new ProductResource($product), 'message' => 'Created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $id = $request->input('id');
        $product = $this->productRepo->find($id);
        return response(['product' => new ProductResource($product), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:255|unique:products',
            'price' => 'required|regex:/^\d{1,8}(\.\d{1,2})?$/'
        ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $id = $request->input('id');

        $product = $this->productRepo->update($id, [
            'name' => $request->input('name'),
            'price' => $request->input('price')
        ]);

        return response(['product' => new ProductResource($product), 'message' => 'Update successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request  $request)
    {
        $id = $request->input('id');
        $this->productRepo->delete($id);

        return response(['message' => 'Deleted']);
    }
}
