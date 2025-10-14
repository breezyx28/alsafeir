<?php
namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::latest()->get();
        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        return view('admin.offers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $path = $request->file('image')->store('public/offers');

        Offer::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_path' => $path,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.offers.index')->with('success', 'تم إضافة العرض بنجاح.');
    }

    public function destroy(Offer $offer)
    {
        Storage::delete($offer->image_path);
        $offer->delete();
        return back()->with('success', 'تم حذف العرض بنجاح.');
    }
}
