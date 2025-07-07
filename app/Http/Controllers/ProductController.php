<?php

namespace App\Http\Controllers;

use App\Constants\ResponseCode;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Utils\ResponseHelper;

class ProductController extends Controller
{

    /**
     * 获取所有商品列表
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_size' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
            'category_id' => 'integer'
        ]);
        if ($validator->fails()) {
            return ResponseHelper::errorResponse(ResponseCode::PAGE_SIZE_INVALID);
        }
        $pageSize = $request->input('page_size', 10);
        $page = $request->input('page', 1);
        $categoryId = $request->input('category_id');
        $query = Product::query();
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        $products = $query->paginate($pageSize, ['*'], 'page', $page);
        $result = [
            'items' => $products->items(),
            'current_page' => $products->currentPage(),
            'total_page' => $products->lastPage(),
            'total' => $products->total()
        ];
        return ResponseHelper::successResponse($result);
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
            return ResponseHelper::errorResponse($validator->errors());
        }

        $product = Product::create($request->all());
        return ResponseHelper::successResponse($product);
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
            return ResponseHelper::successResponse([],ResponseCode::PRODUCT_NOT_FOUND);
        }

        return ResponseHelper::successResponse($product);
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
            return ResponseHelper::successResponse([],ResponseCode::PRODUCT_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric|min:0',
            'stock' => 'integer|min:0',
            'category_id' => 'integer'
        ]);

        if ($validator->fails()) {
            return ResponseHelper::errorResponse($validator->errors());
        }

        $product->update($request->all());
        return ResponseHelper::successResponse([],ResponseCode::SUCCESS);
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
            return ResponseHelper::successResponse([],ResponseCode::PRODUCT_NOT_FOUND);
        }

        $product->delete();
        return ResponseHelper::successResponse([],ResponseCode::SUCCESS);
    }
}