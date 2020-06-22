<?php

namespace Nzesalem\Lastus\Tests;

use Carbon\Carbon;
use Nzesalem\Lastus\Lastus;
use Illuminate\Support\Facades\DB;
use Nzesalem\Lastus\Tests\Models\User;
use Nzesalem\Lastus\Tests\Models\Email;

/**
 * Class BaseTest
 *
 * @package Lastus
 */
class BaseTest extends TestCase
{
    protected $statusFieldName = 'status';
    /**
     * Test basic Lastus functionality.
     */
    public function testStatusMutatorAndAccessor()
    {
        $user = User::create([
            'name' => 'Salem Nzeukwu',
            'email' => 'email@domain.com',
            'password' => bcrypt('secret'),
            // And now Lastus!
            'status' => 'active',
        ]);
        
        $this->assertEquals('active', $user->status);
    }

    /**
     * Test basic Lastus functionality.
     */
    public function testMutatorAndAccessorWithCustomStatusField()
    {
        $user = new User();
        $user->setStatusFieldName('email');

        $user->fill([
            'name' => 'Salem Nzeukwu',
            'email' => 'blocked',
            'password' => bcrypt('secret'),
            'custom_status' => 'blocked',
        ]);
        
        $user->save();
        
        $this->assertEquals('blocked', $user->email);
    }

    /**
     * Test basic Lastus functionality.
     */
    public function testUpdateWithCustomStatusField()
    {
        $user = new User([
            'name' => 'Salem Nzeukwu',
            'email' => 'blocked',
            'password' => bcrypt('secret'),
            'custom_status' => 'blocked',
        ]);

        $user->setStatusFieldName('email');
        $user->save();

        $user->email = 'active';
        
        $this->assertEquals('active', $user->email);
        $this->assertEquals(1, $user->getAttributes()['email']);
    }

    public function testModelStatusCodeMethod()
    {
        $now = Carbon::now();

        // Inserting data this way does not call eloquent mutators
        // so you need to use status code instead of status name
        DB::table('users')->insert([
            'name' => 'Fake Name',
            'email' => 'fake@example.com',
            'password' => bcrypt('secret'),
            'created_at' => $now,
            'updated_at' => $now,
            // Note: Adding a literal string such as 'suspended' here will work
            // this is due to sqlite 'dynamic typing system'
            // But thats definitely not what we want.
            'status' => User::getStatusCode('suspended'),
        ]);

        $user = User::whereRaw('status = ' . User::getStatusCode('suspended'))->first();

        $this->assertEquals('suspended', $user->status);
    }

    public function testInvalidMutatorValue()
    {
        $this->expectException(\InvalidArgumentException::class);
        $user = User::create([
            'name' => 'Firstname Lastname',
            'email' => 'email@domain.com',
            'password' => bcrypt('secret'),
            // Setting status to a number directly is not allowed
            'status' => 1,
        ]);
    }
}
