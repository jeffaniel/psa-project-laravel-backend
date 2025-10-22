@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit User</h1>
            <p class="mt-1 text-sm text-gray-600">Update user information and permissions</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Users
        </a>
    </div>
    
    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- User Info Section -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">User Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $user->name) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $user->email) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number
                        </label>
                        <input type="text" 
                               name="phone" 
                               id="phone" 
                               value="{{ old('phone', $user->phone) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" 
                                id="status" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                </div>
            </div>
            
            <!-- Role Section -->
            <div class="border-t pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Role & Permissions</h2>
                <div>
                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">
                        User Role
                    </label>
                    <select name="role_id" 
                            id="role_id" 
                            class="w-full md:w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role_id') border-red-500 @enderror">
                        <option value="">Select a role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" 
                                {{ $user->roles->contains($role->id) ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        Current roles: 
                        @forelse($user->roles as $role)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $role->display_name }}
                            </span>
                        @empty
                            <span class="text-gray-400">No role assigned</span>
                        @endforelse
                    </p>
                </div>
            </div>
            
            <!-- Account Info -->
            <div class="border-t pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">User ID:</span>
                        <span class="ml-2 font-medium text-gray-900">{{ $user->id }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Created:</span>
                        <span class="ml-2 font-medium text-gray-900">{{ $user->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Last Updated:</span>
                        <span class="ml-2 font-medium text-gray-900">{{ $user->updated_at->format('M d, Y h:i A') }}</span>
                    </div>
                    @if($user->last_login_at)
                    <div>
                        <span class="text-gray-600">Last Login:</span>
                        <span class="ml-2 font-medium text-gray-900">{{ $user->last_login_at->format('M d, Y h:i A') }}</span>
                    </div>
                    @endif
                    @if($user->last_login_ip)
                    <div>
                        <span class="text-gray-600">Last IP:</span>
                        <span class="ml-2 font-medium text-gray-900">{{ $user->last_login_ip }}</span>
                    </div>
                    @endif
                    @if($user->email_verified_at)
                    <div>
                        <span class="text-gray-600">Email Verified:</span>
                        <span class="ml-2 font-medium text-green-600">
                            <svg class="inline w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Yes
                        </span>
                    </div>
                    @else
                    <div>
                        <span class="text-gray-600">Email Verified:</span>
                        <span class="ml-2 font-medium text-red-600">No</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="border-t pt-6 flex gap-3">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                    Cancel
                </a>
            </div>
            
        </form>
    </div>
    
    <!-- Delete User Section (Separate Form) -->
    @if($user->id !== auth()->id())
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-red-600">Danger Zone</h3>
                <p class="mt-1 text-sm text-gray-600">Permanently delete this user account</p>
            </div>
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                    Delete User
                </button>
            </form>
        </div>
    </div>
    @endif
    
</div>
@endsection
