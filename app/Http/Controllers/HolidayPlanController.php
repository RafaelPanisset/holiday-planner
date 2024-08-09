<?php

namespace App\Http\Controllers;

use App\Models\HolidayPlan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\PdfService;

/**
 * @group Holiday Plans
 * Holiday Plan API
 */

class HolidayPlanController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

     /**
     * Get a list of holiday plans
     *
     * Returns a list of all holiday plans
     *
     * @response 200 [{
     *     "id": 1,
     *     "title": "Summer Vacation",
     *     "description": "Family trip to the beach",
     *     "date": "2023-07-01",
     *     "location": "Malibu, CA",
     *     "participants": ["John", "Jane", "Bob"]
     * }]
     */
   
    public function index()
    {
        $holidayPlans = HolidayPlan::all();
        return response()->json($holidayPlans);
    }

    /**
     * Get a holiday plan
     *
     * Returns a single holiday plan
     *
     * @urlParam id integer required The ID of the holiday plan.
     *
     * @response 200 {
     *     "id": 1,
     *     "title": "Summer Vacation",
     *     "description": "Family trip to the beach",
     *     "date": "2023-07-01",
     *     "location": "Malibu, CA",
     *     "participants": ["John", "Jane", "Bob"]
     * }
     * @response 404 {
     *     "message": "Holiday plan not found"
     * }
     */

    public function show($id)
    {
        
        $holidayPlan = HolidayPlan::findOrFail($id);
        return response()->json($holidayPlan);
    }

     /**
     * Create a holiday plan
     *
     * Creates a new holiday plan
     *
     * @bodyParam title string required The title of the holiday plan.
     * @bodyParam description string required The description of the holiday plan.
     * @bodyParam date date required The date of the holiday plan.
     * @bodyParam location string required The location of the holiday plan.
     * @bodyParam participants array The participants of the holiday plan.
     *
     * @response 201 {
     *     "id": 1,
     *     "title": "Summer Vacation",
     *     "description": "Family trip to the beach",
     *     "date": "2023-07-01",
     *     "location": "Malibu, CA",
     *     "participants": ["John", "Jane", "Bob"]
     * }
     * @response 422 {
     *     "errors": {
     *         "title": [
     *             "The title field is required."
     *         ],
     *         "description": [
     *             "The description field is required."
     *         ],
     *         "date": [
     *             "The date field is required."
     *         ],
     *         "location": [
     *             "The location field is required."
     *         ]
     *     },
     *     "message": "Validation errors"
     * }
     * @response 500 {
     *     "errors": "Something went wrong",
     *     "message": "Could not create holiday plan"
     * }
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date_format:Y-m-d',
            'location' => 'required|string|max:255',
            'participants' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation errors',
            ], 422);
        }

        try {
            $holidayPlan = HolidayPlan::create($request->all());
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Something went wrong',
                'message' => 'Could not create holiday plan',
            ], 500);
        }

        return response()->json($holidayPlan, 201);
    }

    /**
     * Update a holiday plan
     *
     * Updates an existing holiday plan
     *
     * @urlParam id integer required The ID of the holiday plan.
     * @bodyParam title string required The title of the holiday plan.
     * @bodyParam description string required The description of the holiday plan.
     * @bodyParam date date required The date of the holiday plan.
     * @bodyParam location string required The location of the holiday plan.
     * @bodyParam participants array The participants of the holiday plan.
     *
     * @response 200 {
     *     "id": 1,
     *     "title": "Summer Vacation",
     *     "description": "Family trip to the beach",
     *     "date": "2023-07-01",
     *     "location": "Malibu, CA",
     *     "participants": ["John", "Jane", "Bob"]
     * }
     * @response 422 {
     *     "errors": {
     *         "title": [
     *             "The title field is required."
     *         ],
     *         "description": [
     *             "The description field is required."
     *         ],
     *         "date": [
     *             "The date field is required."
     *         ],
     *         "location": [
     *             "The location field is required."
     *         ]
     *     },
     *     "message": "Validation errors"
     * }
     * @response 500 {
     *     "errors": "Something went wrong",
     *     "message": "Could not update holiday plan"
     * }
     */

    public function update(Request $request, $id)
    {
        $holidayPlan = HolidayPlan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date_format:Y-m-d',
            'location' => 'required|string|max:255',
            'participants' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation errors',
            ], 422);
        }

        try {
            $holidayPlan->update($request->all());
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Something went wrong',
                'message' => 'Could not update holiday plan',
            ], 500);
        }

        return response()->json($holidayPlan);
    }
     
     /**
     * Delete a holiday plan
     *
     * Deletes an existing holiday plan
     *
     * @urlParam id integer required The ID of the holiday plan.
     *
     * @response 204
     * @response 404 {
     *     "message": "Holiday plan not found"
     * }
     */

    public function destroy($id)
    {
        $holidayPlan = HolidayPlan::findOrFail($id);
        $holidayPlan->delete();
        return response()->noContent();
    }

    /**
     * Generate a PDF for a holiday plan
     *
     * Generates a PDF file for the specified holiday plan
     *
     * @urlParam id integer required The ID of the holiday plan.
     *
     * @response 200 application/pdf
     * @response 404 {
     *     "message": "Holiday plan not found"
     * }
     */

    public function generatePdf($id)
    {
        $holidayPlan = HolidayPlan::findOrFail($id);
        return $this->pdfService->generateHolidayPlanPdf($holidayPlan);
    }
}

