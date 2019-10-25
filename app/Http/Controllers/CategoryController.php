<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    /**
     * UserController constructor.
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        return $this->categoryService->list();
    }

    /**
     * @param CategoryRequest $categoryRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CategoryRequest $categoryRequest)
    {
        return $this->categoryService->create($categoryRequest);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CategoryRequest $categoryRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryRequest $categoryRequest)
    {
        return $this->categoryService->update($categoryRequest);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        return $this->categoryService->delete(json_decode($request->getContent())->category_id);
    }
}
