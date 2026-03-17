{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{$title}}</title>
    <!-- Include Flowbite CDN -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/f5ab145519.js" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="{{ asset('css/student_login.css') }}" type="text/css"> -->
    <!-- <link type="text/css" rel="stylesheet" href="{{ asset('assets/css/student_login.css')}}"> -->
  
</head>


<body class="bg-[#f5f7fe] bg-overlay flex flex-col items-center justify-center font-sans px-4 mt-10">
  <div class="flex items-center space-x-2 mb-8">
    <!-- Logo Image -->
    <img src="{{ asset('assets/images/XcceedAI_logo.png') }}" alt="X Logo" class="w-10 h-10 object-contain">

    <!-- Text Block -->
    <div class="leading-tight">
      <h1 class="text-xl font-bold">XcceedAI</h1>
      <p class="text-xs text-[#40404092]">Today With AI Innovation</p>
    </div>
  </div>

  <div class="bg-white rounded-lg shadow-sm w-full max-w-md p-2 mx-4 mb-1 border-2">
    <h1 class="text-lg font-bold text-center text-blue-700">{{$title}}</h1>
  </div>

  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-8 mx-4 border-2">

    <h2 class="text-xl font-bold mb-6">Reset your password</h2>
    
    <form class="space-y-3" method="POST" action="{{ route('common.savePassword') }}">
      @csrf
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
        <input type="password" id="password" placeholder="••••••••"
               class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
      </div>

      <div>
        <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
        <input type="password" id="confirm_password" placeholder="••••••••" name="confirm_psw"
               class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
      </div>
      <input type="text" name="email" id="email" value="{{ $email }}" class="hidden">
      <input type="text" name="user_type" id="user_type" value="{{ $user_type }}" class="hidden">
      <!-- <div class="flex items-center justify-between">
        <div class="flex items-center">
          <input id="remember" type="checkbox"
                 class="h-4 w-4 text-blue-600 border border-gray-400 rounded focus:ring-blue-500"/>
          <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
        </div>
        <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
      </div> -->

      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-md transition">
        Update Password
      </button>

      <p class="text-sm text-center text-gray-600 mt-4">
        <!-- Don't have an account? -->
        <a href="{{ route( $user_type . '.login') }}" class="text-blue-600 hover:underline">Go back</a>
      </p>
    </form>
  </div>
</body>
</html> --}}


@extends('common.layout')
@section('title-form', 'Reset your password')
@section('content-form')

<form class="space-y-3" method="POST" action="{{ route('common.savePassword') }}">
  @csrf
  <div>
    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
    <input type="password" id="password" placeholder="••••••••"
           class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
  </div>

  <div>
    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
    <input type="password" id="confirm_password" placeholder="••••••••" name="confirm_psw"
           class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
  </div>
  <input type="text" name="email" id="email" value="{{ $email }}" class="hidden">
  <input type="text" name="user_type" id="user_type" value="{{ $user_type }}" class="hidden">
  <!-- <div class="flex items-center justify-between">
    <div class="flex items-center">
      <input id="remember" type="checkbox"
             class="h-4 w-4 text-blue-600 border border-gray-400 rounded focus:ring-blue-500"/>
      <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
    </div>
    <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
  </div> -->

  <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-md transition">
    Update Password
  </button>

  <p class="text-sm text-center text-gray-600 mt-4">
    <!-- Don't have an account? -->
    <a href="{{ route( $user_type . '.login') }}" class="text-blue-600 hover:underline">Go back</a>
  </p>
</form>

@endsection