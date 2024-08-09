<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\HolidayPlanController;
use App\Models\HolidayPlan;
use App\Services\PdfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class HolidayPlanControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;
    protected $pdfService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pdfService = $this->createMock(PdfService::class);
        $this->controller = new HolidayPlanController($this->pdfService);
    }

    public function testIndex()
    {
        HolidayPlan::factory()->count(3)->create();

        $response = $this->controller->index();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(3, $response->getData(true));
    }

    public function testShow()
    {
        $holidayPlan = HolidayPlan::factory()->create();

        $response = $this->controller->show($holidayPlan->id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($holidayPlan->toArray(), $response->getData(true));
    }

    public function testShowNotFound()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->controller->show(999);
    }

    public function testStore()
    {
        $data = [
            'title' => 'Test Holiday',
            'description' => 'Test Description',
            'date' => '2024-12-25',
            'location' => 'Test Location',
            'participants' => ['John', 'Jane']
        ];
    
        $request = new Request($data);
    
        $response = $this->controller->store($request);
    
        $this->assertEquals(201, $response->getStatusCode());
        
        $this->assertDatabaseHas('holiday_plans', [
            'title' => $data['title'],
            'description' => $data['description'],
            'date' => $data['date'],
            'location' => $data['location'],
        ]);
    
        // Check participants separately
        $storedHolidayPlan = HolidayPlan::where('title', $data['title'])->first();
        $this->assertEquals($data['participants'], $storedHolidayPlan->participants);
    }

    public function testStoreValidationFails()
    {
        $data = [
            'title' => '',
            'description' => '',
            'date' => 'invalid-date',
            'location' => '',
        ];

        $request = new Request($data);

        $response = $this->controller->store($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('errors', $response->getData(true));
    }

    public function testUpdate()
    {
        $holidayPlan = HolidayPlan::factory()->create();
    
        $data = [
            'title' => 'Updated Holiday',
            'description' => 'Updated Description',
            'date' => '2024-12-26',
            'location' => 'Updated Location',
            'participants' => ['John', 'Jane', 'Bob']
        ];
    
        $request = new Request($data);
    
        $response = $this->controller->update($request, $holidayPlan->id);
    
        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertDatabaseHas('holiday_plans', [
            'id' => $holidayPlan->id,
            'title' => $data['title'],
            'description' => $data['description'],
            'date' => $data['date'],
            'location' => $data['location'],
        ]);
    
        // Check participants separately
        $updatedHolidayPlan = HolidayPlan::find($holidayPlan->id);
        $this->assertEquals($data['participants'], $updatedHolidayPlan->participants);
    }

    public function testUpdateValidationFails()
    {
        $holidayPlan = HolidayPlan::factory()->create();

        $data = [
            'title' => '',
            'description' => '',
            'date' => 'invalid-date',
            'location' => '',
        ];

        $request = new Request($data);

        $response = $this->controller->update($request, $holidayPlan->id);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('errors', $response->getData(true));
    }

    public function testDestroy()
    {
        $holidayPlan = HolidayPlan::factory()->create();

        $response = $this->controller->destroy($holidayPlan->id);

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertDatabaseMissing('holiday_plans', ['id' => $holidayPlan->id]);
    }

    public function testDestroyNotFound()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->controller->destroy(999);
    }

    public function testGeneratePdf()
    {
        $holidayPlan = HolidayPlan::factory()->create();

        $this->pdfService->expects($this->once())
                ->method('generateHolidayPlanPdf')
                ->with($this->isInstanceOf(HolidayPlan::class))
                ->willReturn(new Response('PDF content', 200, ['Content-Type' => 'application/pdf']));

        $response = $this->controller->generatePdf($holidayPlan->id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }
}