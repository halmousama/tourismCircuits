<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Circuit; // Make sure to import your Circuit model
use App\Models\User;
use Illuminate\Support\Facades\Log; // Import the Log facade
use Illuminate\Support\Facades\Auth; // Import the Auth facade
use Barryvdh\DomPDF\PDF;
use Exception; // Add this line if it's missing

class CircuitController extends Controller
{
    public function store(Request $request)
    {
        // Log the incoming request data
        Log::info('Storing circuit data', ['request' => $request->all()]);

        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string',
            'coord' => 'required|string',
        ]);

        // Retrieve the user by username
        $user = User::where('username', $request->username)->first();

        // Check if the user exists
        if (!$user) {
            Log::warning('User not found', ['username' => $request->username]);
            return redirect()->back()->withErrors(['username' => 'User not found.']);
        }

        // Create a new circuit entry
        Circuit::create([
            'user_id' => $user->id, // Use the retrieved user ID
            'name' => $request->name,
            'coordinates' => $request->coord,
        ]);

        Log::info('Circuit stored successfully', ['user_id' => $user->id, 'coordinates' => $request->coord]);

        return redirect()->back()->with('success', 'Circuit stored successfully!');
    }

    public function getCircuits(Request $request)
    {
        $username = $request->query('username');
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        $circuits = Circuit::where('user_id', $user->id)->get();

        return response()->json(['success' => true, 'circuits' => $circuits]);
    }

    public function destroy($id)
    {
        $circuit = Circuit::find($id);

        if (!$circuit) {
            return response()->json(['success' => false, 'message' => 'Circuit not found.']);
        }

        // Check if the authenticated user is the owner of the circuit
        if ($circuit->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.']);
        }

        // Delete the circuit
        $circuit->delete();

        return response()->json(['success' => true, 'message' => 'Circuit deleted successfully.']);
    }

    public function show($id)
    {
        $circuit = Circuit::find($id);

        if (!$circuit) {
            return redirect()->back()->withErrors(['message' => 'Circuit not found.']);
        }

        $coordinatesArray = explode(';', $circuit->coordinates);
        $coordinates = implode(';', $coordinatesArray);

        $username = Auth::user()->username; // Get the authenticated user's username

        return view('show', compact('circuit', 'username', 'coordinates')); // Pass username to the view
    }

    public function update(Request $request)
    {
        Log::info('request:' . json_encode($request->all()) . '');
        $circuit = Circuit::find($request->id);
        if (!$circuit) {
            return redirect()->back()->withErrors(['message' => 'Circuit not found.']);
        }

        Log::info('Circuit found:' . json_encode($circuit->all()) . '');

        try {
            // Decode the coordinates if they are in JSON format
            if (isset($request->coord)) {
                $coordinates = explode(';', $request->coord);
                $circuit->coordinates = implode(';', $coordinates);
            }

        } catch (Exception $e) {
            Log::error('Error updating circuit:', ['exception' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to update circuit.']);
        }
        Log::info('Updated circuit:' . $circuit->all() . '');

        $circuit->update($request->all());
        return redirect()->back()->with('success', 'Circuit updated successfully.');
    }

    public function export($id)
    {
        $circuit = Circuit::find($id);
        if (!$circuit) {
            return redirect()->back()->withErrors(['message' => 'Circuit not found.']);
        }

        // Pass the circuit data to the export view
        $html = view('export', [
            'circuit' => $circuit,
            'username' => auth()->user()->name, // Assuming you want to pass the username
            'coordinates' => $circuit->coordinates // Pass coordinates if needed
        ])->render(); // Render the view to a string

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . $circuit->name . '-circuit.html"');
    }
    
    public function downloadPdf($id)
    {
        $circuit = Circuit::find($id);
        if (!$circuit) {
            return redirect()->back()->withErrors(['message' => 'Circuit not found.']);
        }

        // Load the view and pass the circuit data
        $pdf = app(PDF::class)->loadView('export', [
            'circuit' => $circuit,
            'username' => auth()->user()->name,
            'coordinates' => $circuit->coordinates // Ensure coordinates are passed
        ]);

        // Download the PDF
        return $pdf->download($circuit->name . '-circuit.pdf');
    }
}
