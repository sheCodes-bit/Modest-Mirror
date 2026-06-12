@extends('layouts.admin')

@section('title', 'Categories Management - ModestMirror')
@section('page_title', 'Category Directory')

@section('content')
<!-- Feedback Alerts -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 mb-4 p-3 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger border-0 rounded-4 mb-4">
        <ul class="mb-0 list-unstyled">
            @foreach ($errors->all() as $error)
                <li><i class="fa-solid fa-circle-exclamation me-2"></i>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="text-muted mb-0">Total: {{ $categories->count() }} Categories</h5>
    <button type="button" class="btn btn-luxury btn-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
        <i class="fa fa-plus me-1"></i> Add Category
    </button>
</div>

<!-- Category Directory Grid -->
<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                <tr>
                    <th class="p-3 border-0">Name</th>
                    <th class="p-3 border-0">Slug</th>
                    <th class="p-3 border-0">Description</th>
                    <th class="p-3 border-0 text-center">Products Linked</th>
                    <th class="p-3 border-0 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="p-3 border-bottom-0"><strong>{{ $category->name }}</strong></td>
                        <td class="p-3 border-bottom-0 text-muted">{{ $category->slug }}</td>
                        <td class="p-3 border-bottom-0 text-truncate" style="max-width: 250px;">{{ $category->description ?? 'No description provided.' }}</td>
                        <td class="p-3 border-bottom-0 text-center fw-semibold">{{ $category->products_count }}</td>
                        <td class="p-3 border-bottom-0 text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-sm btn-luxury-outline py-1 px-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                                    Edit
                                </button>
                                <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category? All linked products will be removed!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger py-1 px-3">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $category->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 border-0">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title brand-font fs-3" id="editModalLabel{{ $category->id }}">Modify Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="name{{ $category->id }}" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Category Name</label>
                                            <input type="text" class="form-control form-control-luxury" id="name{{ $category->id }}" name="name" value="{{ $category->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="desc{{ $category->id }}" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Description</label>
                                            <textarea class="form-control form-control-luxury" id="desc{{ $category->id }}" name="description" rows="3">{{ $category->description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-luxury-outline btn-sm" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-luxury btn-sm">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No categories created.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title brand-font fs-3" id="createCategoryModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_name" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Category Name</label>
                        <input type="text" class="form-control form-control-luxury" id="new_name" name="name" placeholder="e.g. Silk Chiffon Elite" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_desc" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Description</label>
                        <textarea class="form-control form-control-luxury" id="new_desc" name="description" rows="3" placeholder="Enter category highlights..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-luxury-outline btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-luxury btn-sm">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
