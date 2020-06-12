<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AutoCreateAdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function if_env_variables_are_set_we_automatically_create_an_admin_user()
    {
        $this->markTestSkipped('TODO');
    }
}
