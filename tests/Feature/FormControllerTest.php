<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FormControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_submit_form_data()
    {
        $data = [
            'company_symbol' => 'AAPL',
            'start_date' => '2022-01-01',
            'end_date' => '2022-01-31',
            'email' => $this->faker->safeEmail,
        ];

        $response = $this->post(route('submit'), $data);

        $response->assertStatus(200);
        $response->assertViewIs('result');
        $response->assertViewHas('historicalData');
    }

    /** @test */
    public function it_requires_all_fields_to_be_filled()
    {
        $response = $this->post(route('submit'), []);

        $response->assertSessionHasErrors(['company_symbol', 'start_date', 'end_date', 'email']);
    }

    /** @test */
    public function it_requires_a_valid_company_symbol()
    {
        $data = [
            'company_symbol' => 'INVALID',
            'start_date' => '2022-01-01',
            'end_date' => '2022-01-31',
            'email' => $this->faker->safeEmail,
        ];

        $response = $this->post(route('submit'), $data);

        $response->assertSessionHasErrors(['company_symbol']);
    }

    /** @test */
    public function it_requires_a_valid_start_date()
    {
        $data = [
            'company_symbol' => 'AAPL',
            'start_date' => 'INVALID',
            'end_date' => '2022-01-31',
            'email' => $this->faker->safeEmail,
        ];

        $response = $this->post(route('submit'), $data);

        $response->assertSessionHasErrors(['start_date']);
    }

    /** @test */
    public function it_requires_a_valid_end_date()
    {
        $data = [
            'company_symbol' => 'AAPL',
            'start_date' => '2022-01-01',
            'end_date' => 'INVALID',
            'email' => $this->faker->safeEmail,
        ];

        $response = $this->post(route('submit'), $data);

        $response->assertSessionHasErrors(['end_date']);
    }

    /** @test */
    public function it_requires_a_start_date_before_or_equal_to_end_date()
    {
        $data = [
            'company_symbol' => 'AAPL',
            'start_date' => '2022-02-01',
            'end_date' => '2022-01-31',
            'email' => $this->faker->safeEmail,
        ];

        $response = $this->post(route('submit'), $data);

        $response->assertSessionHasErrors(['start_date']);
    }

    /** @test */
    public function it_requires_a_start_date_before_or_equal_to_today()
    {
        $data = [
            'company_symbol' => 'AAPL',
            'start_date' => date('Y-m-d', strtotime('+1 day')),
            'end_date' => '2022-01-31',
            'email' => $this->faker->safeEmail,
        ];

        $response = $this->post(route('submit'), $data);

        $response->assertSessionHasErrors(['start_date']);
    }

   
}