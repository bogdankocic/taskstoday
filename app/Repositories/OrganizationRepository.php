<?php

namespace App\Repositories;

use App\Http\Requests\OrganizationCreateRequest;
use App\Http\Requests\OrganizationUpdateRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrganizationRepository extends BaseRepository
{
    public function __construct(Organization $model)
    {
        parent::__construct($model);
    }

    public function create(OrganizationCreateRequest $request): OrganizationResource
    {
        return new OrganizationResource(
            Organization::create([
            'name' => $request->name,
        ])
    );
    }

    public function delete(Organization $organization): void
    {
        $organization->delete();
    }

    public function update(Organization $organization, OrganizationUpdateRequest $request, string|null $profilePhotoPath): OrganizationResource
    {
        $organization->name = $request->name;
        $organization->email = $request->email;
        $organization->profile_photo = $profilePhotoPath;

        $organization->save();

        return new OrganizationResource($organization);
    }

    public function get(): ResourceCollection
    {
        return OrganizationResource::collection(Organization::all());
    }

    public function getOneModel(int $id): Organization
    {
        return Organization::find($id);
    }

    public function getOne(Organization $organization): OrganizationResource
    {
        return new OrganizationResource($organization);
    }
}