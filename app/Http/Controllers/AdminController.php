<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function login()
    {
        return view('backend.login');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }

    public function userDashboard(){

        $user = auth()->user();
        return view('backend.dashboard', compact('user'));
    }

    public function addClient(){
        return view('backend.client.addEdit');
    }

    public function getClientList(){
        $clients = Client::all();
        return view('backend.client.list', compact('clients'));
    }

    public function createClient(Request $request)
    {
        // Validation rules
        // $rules = [
        //     'name' => 'required|string|max:255',  // Name validation
        //     'type' => 'required|string|max:100',  // Type validation
        //     'website' => 'required|url',         // Website validation
        //     'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image validation
        //     'score' => 'required|numeric',       // Score validation
        //     'date' => 'required|date',           // Date validation
        // ];

        // // Validate incoming request
        // $request->validate($rules);

        try {
            // if ($request->hasFile('image_url')) {
            //     $imageFolder = 'images';
            //     $storagePath = storage_path('app/public/' . $imageFolder);

            //     if (!is_dir($storagePath)) {
            //         mkdir($storagePath, 0755, true);
            //     }
            //     $filename = Str::uuid() . '.' . $request->file('image_url')->getClientOriginalExtension();

            //     // Store the image with the new unique filename
            //     $imagePath = $request->file('image_url')->storeAs($imageFolder, $filename, 'public');
            // }

            // Create a new Client instance
            $client = new Client;

            // Assign values to model attributes
            $client->name = $request->input('name');
            $client->email = $request->input('email');
            $client->designation = $request->input('designation');
            $client->website = $request->input('website');
            $client->date = $request->input('date');
            // $client->pdf_generated = $request->input('pdf_generated');
            $client->save();

            // Return a success response
            return redirect()->back()->with('success', 'Client created successfully.');
        } catch (\Throwable $th) {
            // Handle error
            return redirect()->back()->with('error', 'Error creating Client: ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $client = Client::where('id', $id)->firstOrFail();
            return view('backend.client.addEdit', compact('client'));
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }


    public function updateUser(Request $request)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($request->input('user_id')),
            ],
        ];

        // Custom validation messages
        $messages = [
            'user_id.exists' => 'Invalid user ID.',
            'email.unique' => 'The email address is already in use by another user.',
        ];

        // Validate the request
        $this->validate($request, $rules, $messages);

        try {
            $user_id = $request->input('user_id');
            // Find the user by ID
            $user = User::findOrFail($user_id);

            // Update the user attributes directly
            $user->update([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
            ]);

            return redirect()->back()->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            // Handle exceptions, log the error, or customize the error message as needed
            return redirect()->back()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }
}
