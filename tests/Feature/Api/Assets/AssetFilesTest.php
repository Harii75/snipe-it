<?php

namespace Tests\Feature\Api\Assets;

use Tests\TestCase;
use App\Models\User;
use App\Models\Asset;
use Illuminate\Http\UploadedFile;

class AssetFilesTest extends TestCase
{
    public function testAssetApiAcceptsFileUpload()
    {
        // Upload a file to an asset

        // Create an asset to work with
        $asset = Asset::factory()->count(1)->create();

	// Create a superuser to run this as
	$user = User::factory()->superuser()->create();

	//Upload a file
	$this->actingAsForApi($user)
            ->post(
               route('api.assets.files', ['asset_id' => $asset[0]["id"]]), [
		       'file' => [UploadedFile::fake()->create("test.jpg", 100)]
	       ])
	       ->assertOk();
    }

    public function testAssetApiListsFiles()
    {
        // List all files on an asset
        
        // Create an asset to work with
        $asset = Asset::factory()->count(1)->create();

	// Create a superuser to run this as
	$user = User::factory()->superuser()->create();
	$this->actingAsForApi($user)
            ->getJson(
		    route('api.assets.files', ['asset_id' => $asset[0]["id"]]))
                ->assertOk()
		->assertJsonStructure([
                    'status',
		    'messages',
		    'payload',
		]);
    }

    public function testAssetApiDownloadsFile()
    {
        // Download a file from an asset

        // Create an asset to work with
        $asset = Asset::factory()->count(1)->create();

	// Create a superuser to run this as
	$user = User::factory()->superuser()->create();

	//Upload a file
	$this->actingAsForApi($user)
            ->post(
               route('api.assets.files', ['asset_id' => $asset[0]["id"]]), [
		       'file' => [UploadedFile::fake()->create("test.jpg", 100)]
	       ])->assertOk();

	// Get the file
	$this->actingAsForApi($user)
            ->get(
               route('api.assets.file', [
                   'asset_id' => $asset[0]["id"],
                   'file_id' => 1,
	       ]))
	       ->assertOk();
    }

    public function testAssetApiDeletesFile()
    {
        // Delete a file from an asset

        // Create an asset to work with
        $asset = Asset::factory()->count(1)->create();

	// Create a superuser to run this as
	$user = User::factory()->superuser()->create();

	//Upload a file
	$this->actingAsForApi($user)
            ->post(
               route('api.assets.files', ['asset_id' => $asset[0]["id"]]), [
		       'file' => [UploadedFile::fake()->create("test.jpg", 100)]
	       ])
	       ->assertOk();

	// Delete the file
	$this->actingAsForApi($user)
            ->delete(
               route('api.assets.file', [
                   'asset_id' => $asset[0]["id"],
                   'file_id' => 1,
	       ]))
	       ->assertOk();
    }
}
