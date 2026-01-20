<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiscountOfferRequest;
use App\Http\Requests\DiscountOfferUpdateRequest;
use App\Models\DiscountOffer;
use App\Models\Product;
use App\Services\DiscountOfferService;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function __construct(protected DiscountOfferService $discountOfferService) {}

    public function storeDiscountOffer(DiscountOfferRequest $request)
    {
        $data = $request->validated();

        $this->discountOfferService->storeDiscountOffer($data);

        return redirect()
            ->route('discounts')
            ->with('success', 'Discount offers created successfully.');
    }

    public function showCreateDiscountForm(Request $request)
    {
        $search = $request->input('search');
        $productList = $this->discountOfferService->showCreateDiscountForm($search);

        return view('pages.create-discount-offer', compact('productList'));
    }

    public function getDiscountedProducts(Request $request)
    {
        $perPage = $request->input('perPage', 5);
        $discountedOffers = $this->discountOfferService->getDiscountedProducts($perPage);

        return view('pages.discount-offer', compact('discountedOffers'));
    }

    public function toggleDiscountStatus(DiscountOffer $discount)
    {
        $this->discountOfferService->toggleDiscountStatus($discount);

        return redirect()
            ->route('discounts')
            ->with('success', 'Discount offer status updated successfully.');

    }

    public function deleteDiscountOffer(DiscountOffer $discount)
    {
        $discount->delete();

        return redirect()
            ->route('discounts')
            ->with('success', 'Discount offer deleted successfully.');
    }

    public function allDiscountsDeactivated()
    {
        DiscountOffer::query()->update(['is_active' => false]);

        return redirect()
            ->route('discounts')
            ->with('success', 'All discount offers have been deactivated.');
    }

    public function editDiscountOffer(DiscountOffer $discount)
    {
        $productList = Product::latest()->get();
        $discount->load('discountable');

        return view('pages.edit-discount-offer', compact('discount', 'productList'));
    }

    public function updateDiscountOffer(DiscountOfferUpdateRequest $request, DiscountOffer $discount)
    {
        $data = $request->validated();

        $this->discountOfferService->updateDiscountOffer($data, $discount);

        return redirect()
            ->route('discounts')
            ->with('success', 'Discount offer updated successfully.');
    }
}
