<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\PurchaseStore;
use App\Models\SubCategory;
use App\Models\Supplier;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function ProductAll()
    {
        $productAll = Product::latest()->get();
        return view('admin.product.product_all', compact('productAll'));
    }


    public function ProductAdd()
    {
        $categories = Category::all();
        $units = Unit::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        return view('admin.product.product_add', compact('categories', 'units', 'suppliers'));
    }

    public function ProductStore(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'name' => 'unique:products,name'
                ],
                [
                    'name' => 'Product name has already been taken.',
                    'name.required' => 'Product name is required.',
                ]
            );

            if ($request->file('image')) {
                $image = $request->file('image');
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

                Image::make($image)->resize(200, 200)->save(public_path('upload/product_images/' . $name_gen));
                $save_url = $name_gen;
                // $save_url = 'upload/product_images/' . $name_gen;

                $product = new Product;
                $product->name = $request->name;
                $product->image = $save_url;
                $product->category_id = $request->category_id;
                $product->supplier_id = $request->supplier_id;
                $product->unit_id = $request->unit_id;
                $product->quantity = '0';
                $product->created_by = Auth::user()->id;
                $product->created_at = Carbon::now();
                $product->save();
            }
            $notification = [
                'message' => 'Product Inserted Successfully',
                'alert_type' => 'success',
            ];
            DB::commit();
            return redirect()->route('product.all')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: adding product ' . $e->getMessage() . ' Line: ' . $e->getLine());
            $notification = [
                'message' => 'Something went wrong',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }
    }

    public function ProductEdit($id)
    {
        $category = Category::all();
        $product = Product::findOrFail($id);
        $units = Unit::all();
        $suppliers = Supplier::all();
        return view('admin.product.product_edit', compact('category', 'product', 'units', 'suppliers'));
    }

    public function ProductUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $product_id = $request->id;
            $user_id = Auth::user()->id;

            $request->validate([
                'name' => Rule::unique('products')->ignore($user_id),
            ]);


            if ($request->file('image')) {
                $image = $request->file('image');
                $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();


                $existing_image = Product::findOrFail($product_id);
                @unlink('upload/product_images/' . $existing_image->image);

                Image::make($image)->resize(200, 200)->save(public_path('upload/product_images/' . $name_gen));
                $save_url = $name_gen;

                Product::findOrFail($product_id)->update([
                    'name' => $request->name,
                    'unit_id' => $request->unit_id,
                    'image' => $save_url,
                    'category_id' => $request->category_id,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => Carbon::now(),
                ]);

                $notification = [
                    'message' => 'Product Updated Successfully',
                    'alert_type' => 'success',
                ];
            } else {
                Product::findOrFail($product_id)->update([
                    'name' => $request->name,
                    'unit_id' => $request->unit_id,
                    'category_id' => $request->category_id,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => Carbon::now(),
                ]);

                $notification = [
                    'message' => 'Product Updated Successfully Without Image',
                    'alert_type' => 'success',
                ];
            }

            DB::commit();
            return redirect()->route('product.all')->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: updating product ' . $e->getMessage() . ' Line: ' . $e->getLine());
            $notification = [
                'message' => 'Something went wrong',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }
    }

    public function ProductDelete($id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            if ($product->image != NULL) {
                @unlink(public_path('upload/product_images/' . $product->image));
            }
            $product->delete();

            $notification = [
                'message' => 'Product Deleted Successfully',
                'alert_type' => 'success',
            ];
            DB::commit();
            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: deleting product ' . $e->getMessage() . ' Line: ' . $e->getLine());
            $notification = [
                'message' => 'Something went wrong',
                'alert-type' => 'error'
            ];
            return redirect()->back()->with($notification);
        }
    }

    public function GetSubCategory(Request $request)
    {
        $category_id = $request->category_id;
        $allSubCat = SubCategory::where('category_id', $category_id)->get();
        return response()->json($allSubCat);
    }



    public function GetProductStock($id)
    {
        // $productStock = Product::where('id', $id)->get();
        $productStock = PurchaseStore::where('product_id', $id)->sum('quantity');
        return response()->json($productStock);
    }

    public function ProductStockAll()
    {
        $products = Product::orderBy('name', 'asc')->paginate(15);
        return view('admin.stock.stock_all', compact('products'));

        // $products = Product::orderBy('name', 'asc')->with('unit')->get();

        // // Collect product IDs
        // $productIds = $products->pluck('id');

        // // Get total stock and value per product (fewer queries!)
        // $stockData = PurchaseStore::whereIn('product_id', $productIds)
        //     ->where('quantity', '!=', 0)
        //     ->selectRaw('product_id, SUM(quantity) as total_quantity, SUM(quantity * unit_price) as total_value')
        //     ->groupBy('product_id')
        //     ->get()
        //     ->keyBy('product_id'); // So we can access like $stockData[product_id]

        // return view('admin.stock.stock_all', compact('products', 'stockData'));
    }


    public function GetProduct($id)
    {
        $products = Product::where('category_id', $id)->get();
        return response()->json($products);
    }


    public function ProductSales()
    {
        $products = Product::OrderBy('name', 'asc')->get();

        // $purchaseMeta = PurchaseMeta::get();
        // foreach ($purchaseMeta as $key => $purchase) {
        //     $purchaseStore = PurchaseStore::where('product_id', $purchase->product_id)->where('purchase_id',$purchase->purchase_id)->first();
        //     $purchaseStore->quantity = $purchase->quantity;
        //     $purchaseStore->update();
        // }

        // foreach ($products as $product) {

        //     $salesProduct = InvoiceDetail::where('product_id', $product->id)->sum('selling_qty');


        //     $productInfo = PurchaseStore::where('product_id', $product->id)->where('quantity', '!=', 0)->get();


        //     $fifoStock = $salesProduct; //200 //165
        //     foreach ($productInfo as  $purchaseInfo) {


        //         if ($fifoStock > $purchaseInfo->quantity) {
        //             $fifoStock = abs($fifoStock - (float) $purchaseInfo->quantity);
        //             $purchaseInfo->update([
        //                 'quantity' => 0,
        //             ]);
        //         } else {
        //             $purchaseInfo->update([
        //                 'quantity' => (float) $purchaseInfo->quantity - $fifoStock,
        //             ]);
        //             break;
        //         }
        //     }
        // }

        // foreach ($products as $product) {

        //     $productInfo = PurchaseStore::where('product_id', $product->id)->where('quantity', '!=', 0)->sum('quantity');
        //     $currentStock = (int) $productInfo;
        //     $product->quantity = $productInfo;
        //     $product->update();
        // }
        return view('admin.product.product_sales', compact('products'));
    }
}
