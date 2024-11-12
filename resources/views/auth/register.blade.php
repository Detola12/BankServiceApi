@extends('layouts.app')


@section('content')

    <section class="container ml-[560px] my-28">
        <div>
            <h1 class="text-3xl font-bold text-blue-600">Get Started</h1>
            <p>Put your money to work. It's free to sign up for a Vale Account</p>
        </div>
        <div class="mt-14">
            <form action="">
                <div class="mt-5">
                    <label for="FirstName" class="font-bold text-lg">First Name</label>
                    <input type="text" placeholder="Enter First Name" class="mt-2 block w-1/2 rounded-md border-0 py-2.5 pl-4 pr-14 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-0 focus:ring-inset focus:ring-indigo-400 sm:text-sm sm:leading-6 bg-slate-100">
                </div>
                <div class="mt-5">
                    <label for="LastName" class="font-bold text-lg">Last Name</label>
                    <input type="text" placeholder="Enter Last Name" class="mt-2 block w-1/2 rounded-md border-0 py-2.5 pl-4 pr-14 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-0 focus:ring-inset focus:ring-indigo-400 sm:text-sm sm:leading-6 bg-slate-100">
                </div>
                <div class="mt-5">
                    <label for="Email" class="font-bold text-lg">Email</label>
                    <input type="text" placeholder="Enter your Email" class="mt-2 block w-1/2 rounded-md border-0 py-2.5 pl-4 pr-14 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-0 focus:ring-inset focus:ring-indigo-400 sm:text-sm sm:leading-6 bg-slate-100">
                </div>
                <div class="mt-5">
                    <label for="Phone" class="font-bold text-lg">Phone Number</label>
                    <input type="text" placeholder="Enter Phone Number" class="mt-2 block w-1/2 rounded-md border-0 py-2.5 pl-4 pr-14 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-0 focus:ring-inset focus:ring-indigo-400 sm:text-sm sm:leading-6 bg-slate-100">
                </div>
                <div class="mt-5">
                    <label for="Code" class="font-bold text-lg">Referral Code (Optional)</label>
                    <input type="text" placeholder="Enter Code" class="mt-2 block w-1/2 rounded-md border-0 py-2.5 pl-4 pr-14 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-0 focus:ring-inset focus:ring-indigo-400 sm:text-sm sm:leading-6 bg-slate-100">
                </div>
                <div class="mt-10">
                    <button class="block w-1/2 rounded-md bg-blue-600 text-white py-2.5 ">Sign Up</button>
                </div>
                <!-- <div class="">
                    <p>Not new here <a href="">Sign In</a></p>
                </div> -->
            </form>
        </div>

    </section>

@endsection
