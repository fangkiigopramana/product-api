<?php

namespace App\Http\Controllers;

use App\Http\Resources\AssetResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => "Welcome to product api",
            'created by' => "Fangki Igo Pramana",
            'url' => [
                'All access' => [
                    'GET all products' => '/api/products',
                    'GET all categories' => '/api/categories',
                    'GET one products' => '/api/products/{id}',
                    'POST register' => '/register',
                    'POST login' => '/login',

                ],
                'Login required' => [
                    'POST Product' => '/products',
                    'PATCH Product' => '/products/{id}',
                    'DELETE Product' => '/products/{id}',
                    'POST asset' => '/products/{product_id}/assets',
                    'DELETE asset' => '/products/{product_id}/assets/{asset_id}'
                ]
            ],
            'media' => [
                'github' => 'https://github.com/fangkiigopramana',
                'repository' => 'https://github.com/fangkiigopramana/product-api' 
            ]
        ]);
    }
    // Get all product data
    public function products()
    {
        return response()->json([
            'status' => true,
            'message' => 'Successfully retrieved the list of products',
            'data' => ProductResource::collection(Product::with('assets')->orderBy('price', 'desc')->get())
        ], 200);
    }

    // Get one product data 
    public function product($id)
    {
        // Get product and assets data by product id 
        $product = Product::with('assets')->find($id);

        // if product id not found
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        // if product_id is found
        return response()->json([
            'status' => true,
            'message' => 'Product retrieved successfully.',
            'data' => new ProductResource($product),
        ], 200);
    }

    // Get all category data
    public function categories()
    {
        return response()->json([
            'status' => true,
            'message' => 'Successfully retrieved the list of product categories',
            'data' => CategoryResource::collection(Category::withCount('products')->orderByDesc('products_count')->get())
        ], 200);
    }

    public function storeProduct(Request $request)
    {
        // Request validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required|numeric|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // if request not complete or wrong input type
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Generate slug use Str library
        $slug = Str::slug($request->name);

        // Check product in table by slug
        $findProduct = Product::where('slug', $slug)->exists();
        if ($findProduct) {
            return response()->json([
                'status' => false,
                'message' => 'Product with the same name already exists.',
                'data' => ProductResource::collection(Product::with('assets')->orderBy('price', 'desc')->get())
            ], 200);
        }

        // Save product data to product table
        $product = new Product([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'slug' => $slug,
        ]);
        $product->save();

        // Save all image data to product_assets table
        foreach ($request->file('images') as $image) {
            $path = $image->store('assets'); // save file image to local storage and generate path
            $asset = new ProductAsset([
                'product_id' => $product->id,
                'image' => $path,
            ]);
            $product->assets()->save($asset);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product successfully created.',
            'data' => new ProductResource($product->load('assets')),
        ], 201);
    }

    public function updateProduct(Request $request, $id)
    {
        // Validation data
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'category_id' => 'numeric|exists:categories,id',
            'price' => 'numeric|min:0',
        ]);

        // if validation failed
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check product by id
        $product = Product::findOrFail($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        // Add data request in updateData array
        $updateData = [];
        if ($request->has('name')) {
            $updateData['name'] = $request->name;
            $updateData['slug'] = Str::slug($request->name);
        }
        if ($request->has('category_id')) {
            $updateData['category_id'] = $request->category_id;
        }
        if ($request->has('price')) {
            $updateData['price'] = $request->price;
        }

        // update data
        $product->update($updateData);
        return response()->json([
            'status' => true,
            'message' => 'Product successfully updated.',
            'data' => new ProductResource($product),
        ], 200);
    }

    public function destroyProduct($id)
    {
        // Get product and assets data by id 
        $product = Product::with('assets')->find($id);

        // Check product data
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        // Delete product and assets data
        $product->assets()->delete();
        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product and associated assets have been deleted.',
        ], 200);
    }

    public function storeAsset(Request $request, $product_id)
    {
        // Get product data and check by id
        $product = Product::find($product_id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        // Image datas validation
        $validator = Validator::make($request->all(), [
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        // if failed
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Add image datas to product_assets table
        $assets = [];
        foreach ($request->file('images') as $image) {
            $path = $image->store('assets');
            $asset = new ProductAsset([
                'product_id' => $product_id,
                'image' => $path,
            ]);
            $product->assets()->save($asset);
            $assets[] = new AssetResource($asset);
        }

        return response()->json([
            'status' => true,
            'message' => 'Assets successfully stored.',
            'data' => $assets,
        ], 201);
    }

    public function destroyAsset($product_id, $asset_id)
    {
        // Get product asset data and check by id
        $asset = Product::with('assets')->find($product_id)->assets->find($asset_id);
        if (!$asset) {
            return response()->json([
                'status' => false,
                'message' => 'Asset not found.',
            ], 404);
        }

        // Delete asset
        $asset->delete();

        return response()->json([
            'status' => true,
            'message' => 'Asset have been deleted.',
        ], 200);
    }
}
