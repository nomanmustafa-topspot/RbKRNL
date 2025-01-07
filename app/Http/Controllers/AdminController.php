<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Report;
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
        $clinetsCount = Client::count();
        $reportsCount = Report::distinct('client_id')->count('client_id');
        return view('backend.dashboard', compact('user', 'clinetsCount', 'reportsCount'));
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
        $rules = [
            'name' => 'required|string|max:255',   // Name validation
            'designation' => 'required|string|max:100', // Designation validation
            'website' => 'required|url',          // Website validation
            'date' => 'required|date',            // Date validation
        ];

        // Validate the incoming request
        $request->validate($rules);

        try {
            // Create a new Client instance
            $client = new Client();
            $client->name = $request->input('name');
            $client->email = $request->input('email');
            $client->designation = $request->input('designation');
            $client->website = $request->input('website');
            $client->date = $request->input('date');
            $client->save();

            // Check if the request is AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'newItem' => $client,
                    'message' => 'Client created successfully.',
                ]);
            }


        } catch (\Throwable $th) {
            // Handle errors
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating Client: ' . $th->getMessage(),
                ], 500);
            }
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

    public function deleteClient(Request $request)
    {
        $client = Client::find($request->input('id'));
        if ($client) {
            $client->delete();
            return response()->json(['success' => 'Client deleted successfully.']);
        }
        return response()->json(['error' => 'Client not found.'], 404);
    }
}
