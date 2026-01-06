<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class AdminSliderController extends Controller
{
    /**
     * Display a listing of the sliders.
     */
    public function index()
    {
        try {
            $sliders = Slider::orderBy('sort_order')->get();
            return view('admin.sliders.index', compact('sliders'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error loading sliders: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new slider.
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created slider in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'button_text' => 'nullable|string|max:50',
                'button_link' => 'nullable|url|max:255',
                'is_active' => 'boolean',
                'sort_order' => 'integer|min:0',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = 'slider_' . time() . '.' . $image->getClientOriginalExtension();
                $path = 'sliders/' . date('Y/m');

                // Create directory if it doesn't exist
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }

                $validated['image'] = $this->processImage($image, $path, $filename);
            }

            Slider::create($validated);

            // Clear sliders cache
            Cache::forget('active_sliders');

            return redirect()->route('admin.sliders.index')
                ->with('success', 'Slider created successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating slider: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified slider.
     */
    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified slider in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'button_text' => 'nullable|string|max:50',
                'button_link' => 'nullable|url|max:255',
                'is_active' => 'boolean',
                'sort_order' => 'integer|min:0',
            ]);

            // Handle image upload if new image is provided
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($slider->image) {
                    $this->deleteImage($slider->image);
                }

                $image = $request->file('image');
                $filename = 'slider_' . time() . '.' . $image->getClientOriginalExtension();
                $path = 'sliders/' . date('Y/m');

                // Create directory if it doesn't exist
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }

                $validated['image'] = $this->processImage($image, $path, $filename);
            }

            $slider->update($validated);

            // Clear sliders cache
            Cache::forget('active_sliders');

            return redirect()->route('admin.sliders.index')
                ->with('success', 'Slider updated successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating slider: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified slider from storage.
     */
    public function destroy(Slider $slider)
    {
        try {
            // Delete image if exists
            if ($slider->image) {
                $this->deleteImage($slider->image);
            }

            $slider->delete();

            // Clear sliders cache
            Cache::forget('active_sliders');

            return redirect()->route('admin.sliders.index')
                ->with('success', 'Slider deleted successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting slider: ' . $e->getMessage());
        }
    }

    /**
     * Delete slider image and thumbnail.
     */
    protected function deleteImage($imagePath)
    {
        try {
            // Delete original
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            // Delete thumbnail
            $pathInfo = pathinfo($imagePath);
            $thumbnailPath = $pathInfo['dirname'] . '/thumbs/' . $pathInfo['basename'];

            if (Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting slider image: ' . $e->getMessage());
        }
    }

    /**
     * Update the sort order of sliders.
     */
    public function updateOrder(Request $request)
    {
        try {
            $order = $request->input('order');

            foreach ($order as $item) {
                Slider::where('id', $item['id'])->update(['sort_order' => $item['position']]);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating sort order: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function processImage($image, $path, $filename)
    {
        // Store original
        $image->storeAs('public/' . $path, $filename);
        $imagePath = $path . '/' . $filename;

        // Only process thumbnail if Intervention Image is available
        if (class_exists('Intervention\Image\Facades\Image')) {
            try {
                // Create thumbnail
                $thumbnail = Image::make($image->getRealPath())
                    ->fit(300, 150, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                // Create thumbnails directory if it doesn't exist
                if (!Storage::disk('public')->exists($path . '/thumbs')) {
                    Storage::disk('public')->makeDirectory($path . '/thumbs');
                }

                Storage::disk('public')->put($path . '/thumbs/' . $filename, $thumbnail->stream());
            } catch (\Exception $e) {
                // Log error but don't fail the request
                \Log::error('Error creating thumbnail: ' . $e->getMessage());
            }
        }

        return $imagePath;
    }
}