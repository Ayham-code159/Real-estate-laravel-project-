<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SubcategoryField;
use App\Http\Controllers\Controller;
use App\Services\Category\CategoryManagementService;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Requests\Category\StoreSubcategoryRequest;
use App\Http\Requests\Category\UpdateSubcategoryRequest;
use App\Http\Requests\Category\StoreSubcategoryFieldRequest;
use App\Http\Requests\Category\UpdateSubcategoryFieldRequest;

class CategoryManagementController extends Controller
{
    public function __construct(
        private CategoryManagementService $categoryService
    ) {}

    public function index()
    {
        $categories = Category::query()
            ->withCount('subcategories')
            ->latest()
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function showCategory(Category $category)
    {
        $subcategories = $category->subcategories()
            ->withCount('fields')
            ->latest()
            ->paginate(10);

        return view('admin.categories.show-category', compact('category', 'subcategories'));
    }



    public function createCategory()
    {
        return view('admin.categories.create-category');
    }

    public function storeCategory(StoreCategoryRequest $request)
    {
        $this->categoryService->createCategory($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function editCategory(Category $category)
    {
        return view('admin.categories.edit-category', compact('category'));
    }

    public function updateCategory(UpdateCategoryRequest $request, Category $category)
    {
        $this->categoryService->updateCategory($category, $request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function deleteCategory(Category $category)
    {
        $this->categoryService->deleteCategory($category);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    public function createSubcategory(Category $category)
    {
        return view('admin.categories.create-subcategory', compact('category'));
    }

    public function storeSubcategory(StoreSubcategoryRequest $request, Category $category)
    {
        $this->categoryService->createSubcategory($category, $request->validated());

        return redirect()
            ->route('admin.categories.show', $category)
            ->with('success', 'Subcategory created successfully.');
    }

    public function editSubcategory(Subcategory $subcategory)
    {
        return view('admin.categories.edit-subcategory', compact('subcategory'));
    }

    public function updateSubcategory(UpdateSubcategoryRequest $request, Subcategory $subcategory)
    {
        $this->categoryService->updateSubcategory($subcategory, $request->validated());

        return redirect()
            ->route('admin.categories.show', $subcategory->category_id)
            ->with('success', 'Subcategory updated successfully.');
    }

    public function deleteSubcategory(Subcategory $subcategory)
    {
        $categoryId = $subcategory->category_id;

        $this->categoryService->deleteSubcategory($subcategory);

        return redirect()
            ->route('admin.categories.show', $categoryId)
            ->with('success', 'Subcategory deleted successfully.');
    }

    public function createField(Subcategory $subcategory)
    {
        return view('admin.categories.create-field', compact('subcategory'));
    }

    public function storeField(StoreSubcategoryFieldRequest $request, Subcategory $subcategory)
    {
        $this->categoryService->createSubcategoryField($subcategory, $request->validated());

        return redirect()
            ->route('admin.categories.show', $subcategory->category_id)
            ->with('success', 'Dynamic field created successfully.');
    }

    public function editField(SubcategoryField $field)
    {
        return view('admin.categories.edit-field', compact('field'));
    }

    public function updateField(UpdateSubcategoryFieldRequest $request, SubcategoryField $field)
    {
        $this->categoryService->updateSubcategoryField($field, $request->validated());

        return redirect()
            ->route('admin.categories.show', $field->subcategory->category_id)
            ->with('success', 'Dynamic field updated successfully.');
    }

    public function deleteField(SubcategoryField $field)
    {
        $categoryId = $field->subcategory->category_id;

        $this->categoryService->deleteSubcategoryField($field);

        return redirect()
            ->route('admin.categories.show', $categoryId)
            ->with('success', 'Dynamic field deleted successfully.');
    }

    public function showSubcategory(Subcategory $subcategory)
    {
        $subcategory->load('category');

        $fields = $subcategory->fields()
            ->orderBy('sort_order')
            ->paginate(10);

        return view('admin.categories.show-subcategory', compact('subcategory', 'fields'));
    }


}
