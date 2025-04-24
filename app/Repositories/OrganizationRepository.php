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
            'email' => $request->email,
            'profile_photo' => $request->profile_photo ?? null,
        ])
    );
    }

    public function delete(int $id): void
    {
        Organization::find($id)->delete();
    }

    public function update(int $id, OrganizationUpdateRequest $request): OrganizationResource
    {
        $organization = Organization::find($id);
        $organization->name = $request->name;
        $organization->email = $request->email;
        $organization->profile_photo = $request->profile_photo ?? null;

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

    public function getOne(int $id): OrganizationResource
    {
        return new OrganizationResource(Organization::find($id));
    }
}