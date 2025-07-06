<?php

namespace App\Http\Controllers;

use App\Constants\ResponseCode;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * 获取所有商品列表
     *
     * @return Response
     */
    public function index()
    {
        $products = Product::all();
        return response()->json(['data' => $products], 200);
    }

    /**
     * 创建新商品
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $product = Product::create($request->all());
        return response()->json(['data' => $product], 201);
    }

    /**
     * 获取单个商品详情
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => ResponseCode::PRODUCT_NOT_FOUND[1]], ResponseCode::PRODUCT_NOT_FOUND[0]);
        }

        return response()->json(['data' => $product], 200);
    }

    /**
     * 更新商品信息
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => ResponseCode::PRODUCT_NOT_FOUND[1]], ResponseCode::PRODUCT_NOT_FOUND[0]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric|min:0',
            'stock' => 'integer|min:0',
            'category_id' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $product->update($request->all());
        return response()->json(['data' => $product], 200);
    }

    /**
     * 删除商品
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => ResponseCode::PRODUCT_NOT_FOUND[1]], ResponseCode::PRODUCT_NOT_FOUND[0]);
        }

        $product->delete();
        return response()->json(['message' => '商品删除成功'], 200);
    }
}