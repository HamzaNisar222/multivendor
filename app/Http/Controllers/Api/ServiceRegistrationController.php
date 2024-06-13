<?php
namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Models\VendorServiceRegistration;
use App\Http\Requests\VendorServiceRequest;

class ServiceRegistrationController extends Controller
{
    public function index()
    {
    }

    public function create(VendorServiceRequest $request)
    {
        $response = VendorServiceRegistration::registerService($request);

        // Check if the response is an error
        if ($response instanceof \Illuminate\Http\JsonResponse && $response->status() == 409) {
            return $response;
        }


        return response()->json([
            'message' => 'Service Registration submitted successfully'
        ], 201);
    }
    /**
     * Approve a service registration.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        $registration = VendorServiceRegistration::findOrFail($id);

        if (!$registration->approve()) {
            return response()->json(['message' => 'Service Registration is already approved or rejected'], 400);
        }

        return response()->json(['message' => 'Service Registration approved successfully'], 200);
    }

    /**
     * Reject a service registration.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject($id)
    {
        $registration = VendorServiceRegistration::findOrFail($id);

        if (!$registration->reject()) {
            return response()->json(['message' => 'Service Registration is already approved or rejected'], 400);
        }

        return response()->json(['message' => 'Service Registration rejected successfully'], 200);
    }
}
