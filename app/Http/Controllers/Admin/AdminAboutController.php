<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminAboutController extends Controller
{
    /**
     * Display a listing of the about content.
     */
    public function index()
    {
        $abouts = About::all();
        return view('admin.about.index', compact('abouts'));
    }

    /**
     * Show the form for creating a new about content.
     */
    public function create()
    {
        return view('admin.about.create');
    }

    /**
     * Store a newly created about content in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'history' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/about', $imageName);
            $data['image'] = 'about/' . $imageName;
        }

        // Handle values as JSON
        if ($request->has('values')) {
            $values = [];
            foreach ($request->values as $key => $value) {
                if (!empty($value['title']) && !empty($value['description'])) {
                    $values[] = [
                        'title' => $value['title'],
                        'description' => $value['description']
                    ];
                }
            }
            $data['values'] = $values;
        }

        // Handle team info as JSON
        if ($request->has('team_members')) {
            $teamMembers = [];
            foreach ($request->team_members as $key => $member) {
                if (!empty($member['name']) && !empty($member['position'])) {
                    $teamMembers[] = [
                        'name' => $member['name'],
                        'position' => $member['position'],
                        'image' => $member['image'] ?? null,
                        'bio' => $member['bio'] ?? null
                    ];
                }
            }
            $data['team_info'] = $teamMembers;
        }

        About::create($data);

        return redirect()->route('admin.about.index')->with('success', 'About content created successfully.');
    }

    /**
     * Display the specified about content.
     */
    public function show(About $about)
    {
        return view('admin.about.show', compact('about'));
    }

    /**
     * Show the form for editing the specified about content.
     */
    public function edit(About $about)
    {
        return view('admin.about.edit', compact('about'));
    }

    /**
     * Update the specified about content in storage.
     */
    public function update(Request $request, About $about)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'history' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($about->image && Storage::exists('public/' . $about->image)) {
                Storage::delete('public/' . $about->image);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/about', $imageName);
            $data['image'] = 'about/' . $imageName;
        }

        // Handle values as JSON
        if ($request->has('values')) {
            $values = [];
            foreach ($request->values as $key => $value) {
                if (!empty($value['title']) && !empty($value['description'])) {
                    $values[] = [
                        'title' => $value['title'],
                        'description' => $value['description']
                    ];
                }
            }
            $data['values'] = $values;
        }

        // Handle team info as JSON
        if ($request->has('team_members')) {
            $teamMembers = [];
            foreach ($request->team_members as $key => $member) {
                if (!empty($member['name']) && !empty($member['position'])) {
                    $teamMembers[] = [
                        'name' => $member['name'],
                        'position' => $member['position'],
                        'image' => $member['image'] ?? null,
                        'bio' => $member['bio'] ?? null
                    ];
                }
            }
            $data['team_info'] = $teamMembers;
        }

        $about->update($data);

        return redirect()->route('admin.about.index')->with('success', 'About content updated successfully.');
    }

    /**
     * Remove the specified about content from storage.
     */
    public function destroy(About $about)
    {
        // Delete image if exists
        if ($about->image && Storage::exists('public/' . $about->image)) {
            Storage::delete('public/' . $about->image);
        }

        $about->delete();

        return redirect()->route('admin.about.index')->with('success', 'About content deleted successfully.');
    }
}
