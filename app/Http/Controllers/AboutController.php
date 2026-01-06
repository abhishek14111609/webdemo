<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    /**
     * Display the About Us page.
     */
    public function index()
    {
        // Fetch the active about content (you can change logic if multiple)
        $about = About::where('status', true)->first();

        // If not found, show a 404 or fallback message
        if (!$about) {
            abort(404, 'About page content not found.');
        }

        // Convert JSON fields (values and team_info)
        $about->values = is_array($about->values) ? $about->values : json_decode($about->values, true);
        $about->team_info = is_array($about->team_info) ? $about->team_info : json_decode($about->team_info, true);

        // Image URL
        $about->image_url = $this->getImageUrl($about->image);

        return view('about', compact('about'));
    }

    /**
     * Get full image URL or fallback.
     */
    private function getImageUrl($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }
        return asset('images/no-image.jpg');
    }
}
