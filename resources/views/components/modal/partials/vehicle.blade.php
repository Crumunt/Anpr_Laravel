@props(['id'])

<div>
	<template x-if="currentStep === 1">
		<div class="space-y-4">
			<h3 class="text-lg font-semibold text-gray-800">Select Owner</h3>
			<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
				<div class="md:col-span-3">
					<label class="block text-sm font-medium text-gray-700 mb-1">Search User</label>
					<input type="text" placeholder="Search by name, CLSU ID, or email" x-model="searchQuery"
						@input.debounce.300ms="searchUsers"
						class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" />
					<p class="mt-1 text-xs text-gray-500">Results will appear below. Click a user to select.</p>
				</div>
				<div class="md:col-span-3" x-show="isSearching || (searchResults && searchResults.length)">
					<div class="bg-white border border-gray-200 rounded-md divide-y max-h-60 overflow-y-auto">
						<div class="px-3 py-2 text-sm text-gray-500" x-show="isSearching">Searching...</div>
						<template x-for="user in searchResults" :key="user.id">
							<button type="button" @click="selectUser(user)"
								class="w-full text-left px-3 py-2 hover:bg-green-50">
								<div class="font-medium text-gray-800" x-text="user.name"></div>
								<div class="text-xs text-gray-500" x-text="(user.clsu_id || '') + (user.clsu_id ? ' • ' : '') + user.email"></div>
							</button>
						</template>
						<div class="px-3 py-2 text-sm text-gray-500" x-show="!isSearching && searchResults && searchResults.length === 0 && searchQuery.length >= 2">No users found.</div>
					</div>
				</div>
				<div class="md:col-span-3">
					<label class="block text-sm font-medium text-gray-700 mb-1">Selected Owner</label>
					<input type="text" name="owner" x-model="formData.owner" placeholder="No user selected" readonly
						class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
						:class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.owner}" required />
					<p x-show="errors.owner" x-text="errors.owner" class="mt-1 text-sm text-red-600"></p>
				</div>
			</div>
		</div>
	</template>

	<template x-if="currentStep === 2">
		<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
			<div>
				<label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type</label>
				<select name="vehicle_type" x-model="formData.vehicle_type"
					class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
					:class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.vehicle_type}" required>
					<option value="">Select type</option>
					<option value="car">Car</option>
					<option value="motorcycle">Motorcycle</option>
					<option value="truck">Truck</option>
					<option value="bus">Bus</option>
				</select>
				<p x-show="errors.vehicle_type" x-text="errors.vehicle_type" class="mt-1 text-sm text-red-600"></p>
			</div>
			<div>
				<label class="block text-sm font-medium text-gray-700 mb-1">Make/Brand</label>
				<input type="text" name="make" x-model="formData.make"
					class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
					:class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.make}" required>
				<p x-show="errors.make" x-text="errors.make" class="mt-1 text-sm text-red-600"></p>
			</div>
			<div>
				<label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
				<input type="text" name="model" x-model="formData.model"
					class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
					:class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.model}" required>
				<p x-show="errors.model" x-text="errors.model" class="mt-1 text-sm text-red-600"></p>
			</div>
			<div>
				<label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
				<input type="text" name="color" x-model="formData.color"
					class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
					:class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.color}" required>
				<p x-show="errors.color" x-text="errors.color" class="mt-1 text-sm text-red-600"></p>
			</div>
			<div>
				<label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
				<input type="number" name="year" x-model="formData.year" min="1900" max="2099"
					class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
					:class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.year}" required>
				<p x-show="errors.year" x-text="errors.year" class="mt-1 text-sm text-red-600"></p>
			</div>
			<div>
				<label class="block text-sm font-medium text-gray-700 mb-1">License Plate</label>
				<input type="text" name="license_plate" x-model="formData.license_plate"
					class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
					:class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.license_plate}" required>
				<p x-show="errors.license_plate" x-text="errors.license_plate" class="mt-1 text-sm text-red-600"></p>
			</div>
			<div>
				<label class="block text-sm font-medium text-gray-700 mb-1">Registration Date</label>
				<input type="date" name="registration_date" x-model="formData.registration_date"
					class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
					:class="{'border-red-300 focus:ring-red-500 focus:border-red-500': errors.registration_date}" required>
				<p x-show="errors.registration_date" x-text="errors.registration_date" class="mt-1 text-sm text-red-600"></p>
			</div>
		</div>
	</template>

	<template x-if="currentStep === 3">
		<div class="space-y-4">
			<h3 class="text-lg font-semibold">Review Information</h3>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<div>
					<h4 class="font-medium">Owner</h4>
					<ul class="text-sm">
						<li><strong>Owner:</strong> <span x-text="formData.owner"></span></li>
					</ul>
				</div>
				<div>
					<h4 class="font-medium">Vehicle Info</h4>
					<ul class="text-sm">
						<li><strong>Type:</strong> <span x-text="formData.vehicle_type"></span></li>
						<li><strong>Make:</strong> <span x-text="formData.make"></span></li>
						<li><strong>Model:</strong> <span x-text="formData.model"></span></li>
						<li><strong>Color:</strong> <span x-text="formData.color"></span></li>
						<li><strong>Year:</strong> <span x-text="formData.year"></span></li>
						<li><strong>License Plate:</strong> <span x-text="formData.license_plate"></span></li>
						<li><strong>Registration Date:</strong> <span x-text="formData.registration_date"></span></li>
					</ul>
				</div>
			</div>
		</div>
	</template>

	<div class="flex justify-center gap-4 mt-8">
		<button type="button" class="px-6 py-2 bg-gray-200 text-gray-700 rounded shadow-sm hover:bg-gray-300 transition"
			x-show="currentStep === 1" @click="closeModal('{{ $id }}')">Cancel</button>
		<button type="button" class="px-6 py-2 bg-gray-200 text-gray-700 rounded shadow-sm hover:bg-gray-300 transition"
			x-show="currentStep > 1" @click="prevStep">Back</button>
		<button type="button" class="px-6 py-2 bg-green-600 text-white rounded shadow-sm hover:bg-green-700 transition"
			x-show="currentStep < 3" @click="nextStep">Next</button>
		<button type="button" class="px-6 py-2 bg-green-600 text-white rounded shadow-sm hover:bg-green-700 transition"
			x-show="currentStep === 3 && !isSubmitting" @click="submitForm">Save</button>
		<span x-show="isSubmitting" class="flex items-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
				xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
				<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
				<path class="opacity-75" fill="currentColor"
					d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
				</path>
			</svg>Processing...</span>
	</div>
</div>

