<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        /* -------- payload mentah -------- */
        \Log::debug('REVIEW-RAW', $request->all());

        $validated = $request->validate([
            'order_id'   => ['required','exists:orders,id'],
            'product_id' => [
                'required',
                Rule::exists('order_details','product_id')
                    ->where(fn ($q) => $q->where('order_id', $request->order_id)),
            ],
            'rating'     => ['required','integer','between:1,5'],
            'title'      => ['required','string','max:255'],
            'content'    => ['required','string'],
            'images'     => ['nullable','array','max:3'],                 // ⬅️ maks 3 file
            'images.*'   => ['image','mimes:jpg,jpeg,png','max:2048'],
        ]);

        \Log::debug('REVIEW-VALIDATED', $validated);

        /* -------- guard order -------- */
        $order = Order::findOrFail($validated['order_id']);
        \Log::debug('REVIEW-ORDER', $order->only(['id','user_id','status']));

        abort_if(
            $order->user_id !== $request->user()->id ||
            $order->status   !== 'completed',
            403
        );

        /* -------- dapatkan (atau buat) review -------- */
        $review = Review::firstOrNew([
            'order_id'   => $order->id,
            'product_id' => $validated['product_id'],
        ]);

        /* -------- proses gambar -------- */
        $paths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $paths[] = $img->store('reviews', 'public');
            }

            // Hapus file lama jika edit & user upload baru
            if ($review->exists && $review->images) {
                foreach (json_decode($review->images, true) as $oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

        \Log::debug('REVIEW-MODEL-BEFORE-SAVE', $review->toArray());

        /* -------- simpan data -------- */
        $review->fill([
            'user_id'  => $request->user()->id,
            'rating'   => (int) $validated['rating'],      // cast int
            'title'    => $validated['title'],
            'content'  => $validated['content'],
            'images'   => $paths ? json_encode($paths) : ($review->images ?? null),
            'verified' => true,
        ])->save();

        \Log::debug('REVIEW-MODEL-AFTER-SAVE', $review->toArray());

        return back()->with(
            'success',
            $review->wasRecentlyCreated ? 'Review ditambahkan.' : 'Review diperbarui.'
        );
    }
}
