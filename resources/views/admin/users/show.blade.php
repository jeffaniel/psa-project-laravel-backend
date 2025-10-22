@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
            <p class="mt-1 text-sm text-gray-600">View user information and activity</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit User
            </a>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Users
            </a>
        </div>
    </div>
    
    <!-- User Profile Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8">
            <div class="flex items-center">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mr-6">
                    <span class="text-3xl font-bold text-blue-600">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <div class="text-white">
                    <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                    <p class="text-blue-100">{{ $user->email }}</p>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $user->status === 'active' ? 'bg-green-500 text-white' : '' }}
                            {{ $user->status === 'inactive' ? 'bg-gray-500 text-white' : '' }}
                            {{ $user->status === 'suspended' ? 'bg-red-500 text-white' : '' }}">
                            {{ ucfirst($user->status ?? 'active') }}
                        </span>
                        @foreach($user->roles as $role)
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-500 text-white">
                                {{ $role->display_name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Contact Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->address ?? 'Not provided' }}</dd>
                        </div>
                        @if($user->city || $user->state || $user->country)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Location</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ collect([$user->city, $user->state, $user->country])->filter()->implode(', ') }}
                            </dd>
                        </div>
                        @endif
                        @if($user->postal_code)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Postal Code</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->postal_code }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
                
                <!-- Personal Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">User ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">#{{ $user->id }}</dd>
                        </div>
                        @if($user->date_of_birth)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($user->date_of_birth)->format('M d, Y') }}</dd>
                        </div>
                        @endif
                        @if($user->gender)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Gender</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($user->gender) }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                            <dd class="mt-1 text-sm">
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center text-green-600">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Verified on {{ $user->email_verified_at->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="text-red-600">Not verified</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- Activity Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Activity Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Last Login</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $user->last_login_at ? $user->last_login_at->format('M d, Y h:i A') : 'Never' }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-orange-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Last Logout</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $user->last_logout_at ? $user->last_logout_at->format('M d, Y h:i A') : 'Never' }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Account Status</p>
                        <p class="text-lg font-semibold text-gray-900">{{ ucfirst($user->status ?? 'active') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-purple-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">User Role</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $user->roles->pluck('display_name')->implode(', ') ?: 'No role' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4 pt-4 border-t grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($user->last_login_ip)
            <p class="text-sm text-gray-600">
                <span class="font-medium">Last Login IP:</span> {{ $user->last_login_ip }}
            </p>
            @endif
            @if($user->last_logout_ip)
            <p class="text-sm text-gray-600">
                <span class="font-medium">Last Logout IP:</span> {{ $user->last_logout_ip }}
            </p>
            @endif
        </div>
    </div>
    
</div>
@endsection
