<?php

namespace Tests\Feature;

use App\Model\Product;
use App\Model\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function aUserCanGetAllProductSortedByNameAsc()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        factory(Product::class)->create([
            'name' => 'Product Name 2',
            'value' => 50,
            'cost' => 10,
        ]);
        factory(Product::class)->create([
            'name' => 'Product Name 1',
            'value' => 100,
            'cost' => 60,
        ]);

        $response = $this->get('/api/products');
        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJson([
                [
                    'id' => 2,
                    'name' => 'Product Name 1',
                    'value' => 100,
                    'cost' => 60,
                ],
                [
                    'id' => 1,
                    'name' => 'Product Name 2',
                    'value' => 50,
                    'cost' => 10,
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCanGetAProduct()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $product = factory(Product::class)->create();

        $response = $this->get('/api/products/1');
        $response->assertOk()
            ->assertJson([
                'id' => 1,
                'name' => $product->name,
                'value' => $product->value,
                'cost' => $product->cost,
            ]);
    }

    /**
     * @test
     */
    public function aUserCanCreateAProduct()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => 'Product Name',
            'value' => 100,
            'cost' => 10,
        ];
        $request = $this->post('/api/products', $data);

        $request->assertCreated()
            ->assertJson([
                'name' => 'Product Name',
                'value' => 100,
                'cost' => 10,
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateAProductWithoutTheRequiredFields()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => '',
            'value' => '',
        ];
        $request = $this->post('/api/products', $data);

        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ],
                    'value' => [
                        'The value field is required.'
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCantCreateAProductWithWrongValue()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => 'Product Name',
            'value' => 'value',
        ];
        $request = $this->post('/api/products', $data);

        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'value' => [
                        'The value must be a number.'
                    ],
                ]
            ]);
        
        $data['value'] = 100;
    
        $data['email'] = 'product@product';
        $request = $this->post('/api/products', $data);
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function aUserCantCreateAProductWithWrongCost()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => 'Product Name',
            'value' => 100,
            'cost' => 'cost',
        ];
        $request = $this->post('/api/products', $data);

        $request->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'cost' => [
                        'The cost must be a number.'
                    ],
                ]
            ]);

        $data['cost'] = 10;
    
        $data['email'] = 'product@product';
        $request = $this->post('/api/products', $data);
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function aUserCantCreateAProductWithoutCost()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $data = [
            'name' => 'Product Name',
            'value' => 100,
        ];

        $request = $this->post('/api/products', $data);
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function aUserCanUpdateAProduct()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $product = factory(Product::class)->create();

        $response = $this->get('/api/products');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => $product->name,
                    'value' => $product->value,
                    'cost' => $product->cost,
                ]
            ]);

        $data = [
            'name' => 'Product Name',
            'value' => '200',
            'cost' => '50',
        ];

        $request = $this->put('/api/products/1', $data);
        $request->assertOk()
            ->assertJson([
                'id' => 1,
                'name' => 'Product Name',
                'value' => '200',
                'cost' => '50',
            ]);

        $response = $this->get('/api/products');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => 'Product Name',
                    'value' => '200',
                    'cost' => '50',
                ]
            ]);
    }

    /**
     * @test
     */
    public function aUserCanDeleteAProduct()
    {
        $this->actingAs(factory(User::class)->create(), 'api');
        $product = factory(Product::class)->create();

        $response = $this->get('/api/products');
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'id' => 1,
                    'name' => $product->name,
                    'value' => $product->value,
                    'cost' => $product->cost,
                ]
            ]);

        $request = $this->delete('/api/products/1');
        $request->assertOk()
            ->assertJson([]);

        $response = $this->get('/api/products');
        $response->assertOk()
            ->assertJsonCount(0)
            ->assertJson([]);
    }
}
