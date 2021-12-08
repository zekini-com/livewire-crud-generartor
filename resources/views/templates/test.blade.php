@php echo "<?php";
@endphp

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\{{$modelBaseName}};

class {{$modelBaseName}}Test extends TestCase
{
    /**
     * Test we can add {{$resource}}
     * @group {{$resource}}_test
     * @return void
     */
    public function test_we_can_add_{{$resource}}()
    {
       $data = {{$modelBaseName}}::factory()->make()->toArray();
       $admin = {{$adminModel}}::factory()->create();
       $guard = config('zekini-admin.defaults.guard');
       $uri = url("admin/{{strtolower($resource)}}");
       $response = $this->actingAs($admin, $guard)
                    ->post($uri, $data);
        
        $response->assertStatus(302); //there was a redirection

        $this->assertDatabaseHas("{{$tableName}}", $data); // The given data is now in the database
       
    }

    /**
     * Test we can update {{$resource}}
     * @group {{$resource}}_test
     * @return void
     */
    public function test_we_can_update_{{$resource}}()
    {
        $oldData = {{$modelBaseName}}::factory()->create();

        $data = {{$modelBaseName}}::factory()->make()->toArray();

        $admin = {{$adminModel}}::factory()->create();
        $guard = config('zekini-admin.defaults.guard');
        $uri = url("admin/{{strtolower($resource)}}/".$oldData->id);
        $response = $this->actingAs($admin, $guard)
                     ->post($uri, $data);
         
         $response->assertStatus(302); //there was a redirection
 
         $this->assertDatabaseHas("{{$tableName}}", $data); // The given data is now in the database
       
    }

    /**
     * Test we can delete {{$resource}}
     * @group {{$resource}}_test
     * @return void
     */
    public function test_we_can_delete_{{$resource}}()
    {
        $oldData = {{$modelBaseName}}::factory()->create();

        $admin = {{$adminModel}}::factory()->create();
        $guard = config('zekini-admin.defaults.guard');
        $uri = url("admin/{{strtolower($resource)}}/".$oldData->id);
        $response = $this->actingAs($admin, $guard)
                     ->delete($uri, $data);
         
         $response->assertStatus(302); //there was a redirection
 
         $this->assertDatabaseMissing("{{$tableName}}", $oldData); // The given data is now in the database
       
    }



    /**
     * Test we can view index  {{$resource}} page
     * @group {{$resource}}_test
     * @return void
     */
    public function test_we_can_view_{{$resource}}_page()
    {

        $admin = {{$adminModel}}::factory()->create();
        $guard = config('zekini-admin.defaults.guard');
        $uri = url("admin/{{$resource}}");
        $response = $this->actingAs($admin, $guard)
                     ->get($uri, $data);
         
         $response->assertStatus(200); //there was an okay response
       
    }

    /**
     * Test we can view edit  {{$resource}} page
     * @group {{$resource}}_test
     * @return void
     */
    public function test_we_can_view_edit_{{$resource}}_page()
    {
        $oldData = {{$resource}}::factory()->create();

        $admin = {{$adminModel}}::factory()->create();
        $guard = config('zekini-admin.defaults.guard');
        $uri = url("admin/{{strtolower($resource)}}/".$oldData->id);
        $response = $this->actingAs($admin, $guard)
                     ->get($uri);
         
         $response->assertStatus(200); //there was an okay response
       
    }



}
