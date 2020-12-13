<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    /**
     *
     * @return void
     */
    public function testCreateProductValid(){
        $response = $this->json('POST','/api/products', [
            'name' => 'cong test',
            'price'=> 9000
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('product.name', 'cong test')
            ->assertJsonPath('product.price', 9000);
    }

    /**
     *
     * @return void
     */
    public function testCreateProductInValidName(){
        $response = $this->json('POST','/api/products', [
            'name' => '',
            'price'=> 99999
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('error', ['name' => ['The name field is required.']]);
    }

    /**
     *
     * @return void
     */
    public function testCreateProductInValidPrice(){
        $response = $this->json('POST','/api/products', [
            'name' => 'cong',
            'price'=> ''
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('error', ['price' => ['The price field is required.']]);
    }

    /**
     *
     * @return void
     */
    public function testCreateProductInValidFormatPrice(){
        $response = $this->json('POST','/api/products', [
            'name' => 'cong',
            'price'=> '1000VND'
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('error', ['price' => ['The price format is invalid.']]);
    }


    /**
     *
     * @return void
     */
    public function testCreateProductInValidMaxValuePrice(){
        $response = $this->json('POST','/api/products', [
            'name' => 'cong',
            'price'=> 100000000
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('error', ['price' => ['The price format is invalid.']]);
    }

    /**
     *
     * @return void
     */
    public function testCreateProductInValidNameAndPrice(){
        $response = $this->json('POST','/api/products', [
            'name' => '',
            'price'=> null
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('error', [
                'name' => [
                    'The name field is required.'
                ],
                'price' => [
                    'The price field is required.'
                ]
            ]);
    }

    /**
     *
     * @return void
     */
    public function testCreateProductInValidNameUnique(){
        $response = $this->json('POST','/api/products', [
            'name' => 'cong unique',
            'price'=> 10000
        ]);

        $response->assertStatus(201)
                ->assertJsonPath('message', 'Created successfully');

        $responseAfter = $this->json('POST','/api/products', [
            'name' => 'cong unique',
            'price'=> 20000
        ]);

        $responseAfter->assertStatus(200)
            ->assertJsonPath('error', [
                'name' => [
                    'The name has already been taken.'
                ]
            ]);
    }
}
