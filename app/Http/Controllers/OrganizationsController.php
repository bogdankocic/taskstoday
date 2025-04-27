<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganizationCreateRequest;
use App\Http\Requests\OrganizationUpdateRequest;
use App\Models\Organization;
use App\Repositories\OrganizationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class OrganizationsController extends Controller
{
    protected OrganizationRepository $organizationRepository;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * Get all organizations.
     */
    public function get(): JsonResponse
    {
        if (! Gate::allows('app-admin-only')) {
            abort(403, 'Unauthorized.');
        }

        return response()->json($this->organizationRepository->get());
    }

    /**
     * Get a single organization by ID.
     */
    public function getOne(Organization $organization): JsonResponse
    {
        $organization = $this->organizationRepository->getOne($organization);

        if (! Gate::allows('my-organization', $organization)) {
            abort(403, 'Unauthorized.');
        }

        return response()->json($organization);
    }

    /**
     * Create a new organization.
     */
    public function create(OrganizationCreateRequest $request): JsonResponse
    {
        if (! Gate::allows('app-admin-only')) {
            abort(403, 'Unauthorized.');
        }

        $organization = $this->organizationRepository->create($request);
        return response()->json($organization, 201);
    }

    /**
     * Update an existing organization by ID.
     */
    public function update(Organization $organization, OrganizationUpdateRequest $request): JsonResponse
    {
        $organization = $this->organizationRepository->getOneModel($organization->id);

        if (! Gate::allows('my-organization-and-admin', $organization)) {
            abort(403, 'Unauthorized.');
        }

        $organization = $this->organizationRepository->update($organization, $request);
        return response()->json($organization);
    }

    /**
     * Delete an organization by ID.
     * Only accessible by users with the 'admin-only' permission.
     */
    public function delete(Organization $organization): JsonResponse
    {
        if (! Gate::allows('app-admin-only')) {
            abort(403, 'Unauthorized.');
        }

        $this->organizationRepository->delete($organization);
        return response()->json(['message' => 'Organization deleted successfully'], 200);
    }
}