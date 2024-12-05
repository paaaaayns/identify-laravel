<x-layout>

    <div class="max-w-4xl w-full mx-auto">

        <div class="w-full flex flex-col items-center">
            <form method="POST" action="{{ route('users.opd.store') }}" id="RegistrationForm" class="w-full">
                @csrf
                <div class="bg-white rounded-lg shadow">
                    <!-- Personal Information -->
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800">Personal Information</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-x-6 gap-y-6 mt-6">

                            <x-forms.field-container class="sm:col-span-4">
                                <x-forms.label
                                    for="last_name">
                                    Last Name <span class="text-red-500">*</span>
                                </x-forms.label>

                                <x-forms.input
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    :value="old('last_name')"
                                    autocomplete="off" />
                                <x-forms.error name="last_name" />

                            </x-forms.field-container>

                            <x-forms.field-container class="sm:col-span-4">
                                <x-forms.label
                                    for="first_name">
                                    First Name <span class="text-red-500">*</span>
                                </x-forms.label>

                                <x-forms.input
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    :value="old('first_name')"
                                    autocomplete="off" />
                                <x-forms.error name="first_name" />

                            </x-forms.field-container>

                            <x-forms.field-container class="sm:col-span-4">
                                <x-forms.label
                                    for="middle_name">
                                    Middle Name
                                </x-forms.label>

                                <x-forms.input
                                    type="text"
                                    id="middle_name"
                                    name="middle_name"
                                    :value="old('middle_name') ?? ''"
                                    autocomplete="off" />
                                <x-forms.error name="middle_name" />

                            </x-forms.field-container>

                            <x-forms.field-container class="sm:col-span-6">
                                <x-forms.label
                                    for="birthdate">
                                    Birthdate <span class="text-red-500">*</span>
                                </x-forms.label>

                                <x-forms.input
                                    type="text"
                                    id="birthdate"
                                    name="birthdate"
                                    :value="old('birthdate')"
                                    autocomplete="off"
                                    oninput="restrictLetterInput(this)"
                                    datepicker
                                    datepicker-autohide
                                    datepicker-format="mm-dd-yyyy" />
                                <x-forms.error name="birthdate" />

                            </x-forms.field-container>

                            <x-forms.field-container class="sm:col-span-6">
                                <x-forms.label
                                    for="sex">
                                    Sex <span class="text-red-500">*</span>
                                </x-forms.label>

                                <x-forms.select
                                    id="sex"
                                    name="sex">
                                    <option {{ old('sex') === null ? 'selected' : '' }} value="">Select</option>
                                    <option value="Male" {{ old('sex') === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                                </x-forms.select>
                                <x-forms.error name="sex" />

                            </x-forms.field-container>

                            <x-forms.field-container class="sm:col-span-4">
                                <x-forms.label
                                    for="religion">
                                    Religion <span class="text-red-500">*</span>
                                </x-forms.label>

                                <x-forms.input
                                    type="text"
                                    id="religion"
                                    name="religion"
                                    :value="old('religion')"
                                    autocomplete="off" />
                                <x-forms.error name="religion" />

                            </x-forms.field-container>

                            <x-forms.field-container class="sm:col-span-4">
                                <x-forms.label
                                    for="civil_status">
                                    Civil Status <span class="text-red-500">*</span>
                                </x-forms.label>

                                <x-forms.select
                                    id="civil_status"
                                    name="civil_status">
                                    <option {{ old('civil_status') === null ? 'selected' : '' }} value="">Select</option>
                                    <option value="Single" {{ old('civil_status') === 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('civil_status') === 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Divorced" {{ old('civil_status') === 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                </x-forms.select>
                                <x-forms.error name="civil_status" />

                            </x-forms.field-container>

                            <x-forms.field-container class="sm:col-span-4">
                                <x-forms.label
                                    for="citizenship">
                                    Citizenship <span class="text-red-500">*</span>
                                </x-forms.label>

                                <x-forms.input
                                    type="text"
                                    id="citizenship"
                                    name="citizenship"
                                    :value="old('citizenship')"
                                    autocomplete="off" />
                                <x-forms.error name="citizenship" />

                            </x-forms.field-container>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800">Contact Information</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-x-6 gap-y-6 mt-6">

                            <x-forms.field-container class="sm:col-span-12">
                                <x-forms.label
                                    for="address">
                                    Complete Address <span class="text-red-500">*</span>
                                </x-forms.label>

                                <x-forms.input
                                    type="text"
                                    id="address"
                                    name="address"
                                    :value="old('address')"
                                    autocomplete="off" />
                                <x-forms.error name="address" />

                            </x-forms.field-container>

                            <x-forms.field-container class="sm:col-span-6">
                                <x-forms.label
                                    for="email">
                                    Email <span class="text-red-500">*</span>
                                </x-forms.label>

                                <x-forms.input
                                    type="text"
                                    id="email"
                                    name="email"
                                    :value="old('email')"
                                    autocomplete="off" />
                                <x-forms.error name="email" />

                            </x-forms.field-container>

                            <x-forms.field-container class="sm:col-span-6">
                                <x-forms.label
                                    for="contact_number">
                                    Contact Number <span class="text-red-500">*</span>
                                </x-forms.label>

                                <x-forms.input
                                    type="text"
                                    id="contact_number"
                                    name="contact_number"
                                    :value="old('contact_number')"
                                    maxlength="11"
                                    oninput="restrictLetterInput(this)"
                                    autocomplete="off" />
                                <x-forms.error name="contact_number" />

                            </x-forms.field-container>

                        </div>
                    </div>

                    <!-- Job Information -->
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800">Job Information</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-x-6 gap-y-6 mt-6">

                            <x-forms.field-container class="sm:col-span-6">
                                <x-forms.label
                                    for="type">
                                    Department <span class="text-red-500">*</span>
                                </x-forms.label>

                                <x-forms.input
                                    type="text"
                                    id="type"
                                    name="type"
                                    :value="old('type')"
                                    autocomplete="off" />
                                <x-forms.error name="type" />

                            </x-forms.field-container>

                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-6">
                        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 pt-6">
                            <button
                                id="fill"
                                type="button"
                                onclick="fillFields()"
                                class="rounded-md px-3 py-2 text-sm font-semibold text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                                Test
                            </button>

                            <x-forms.primary-button
                                type="button"
                                onclick="confirmCreate()">
                                Submit
                            </x-forms.primary-button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- Validation -->
    <script>
        function restrictLetterInput(input) {
            // Allow only numbers and dash "-" (for date format like mm-dd-yyyy)
            input.value = input.value.replace(/[^0-9\-\/]/g, '');
        }
    </script>

    <!-- Confirmation dialog -->
    <script>
        // Confirmation dialog
        async function confirmCreate() {
            // Prevent the default form submission
            event.preventDefault();

            const isFormValidated = await validateForm('RegistrationForm', '/register/opd/validate-store-request');
            if (isFormValidated) {
                const isVerified = await promptForPassword();
                if (isVerified) {
                    createUser();

                    // redirect to the list of users
                    clearForm('RegistrationForm');
                }
                return;
            }
        }

        // Creation process
        async function createUser() {
            const form = document.getElementById('RegistrationForm');
            const formData = new FormData(form);

            try {
                // Perform the POST request using Fetch API
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });

                if (response.ok) {
                    const result = await response.json();
                    showToast('toast-success', result.message);
                    console.log('Success:', result.message);
                } else {
                    showToast('toast-error', 'Failed to create the account.');
                    console.error('Error:', 'Failed to create the account.');
                }
            } catch (error) {
                showToast('toast-error', 'An error occurred while processing the request.');
                console.error('Error:', error);
            }
        }
    </script>

    <!-- Test -->
    <script>
        function fillFields() {
            // Predefined values for testing
            const testData = {
                first_name: "John",
                middle_name: "James",
                last_name: "Doe",
                email: "john.doe@example.com",
                birthdate: "10-20-1990",
                sex: "Male",
                religion: "Catholic",
                civil_status: "Single",
                citizenship: "Male",

                address: "4527 Colonial Drive High Hill Texas",
                email: "john.doe@gmail.com",
                contact_number: "09123456789",

                type: "Orthopedic",
            };

            // Fill fields using their IDs
            document.getElementById('first_name').value = testData.first_name;
            document.getElementById('middle_name').value = testData.middle_name;
            document.getElementById('last_name').value = testData.last_name;
            document.getElementById('birthdate').value = testData.birthdate;
            document.getElementById('sex').value = testData.sex;
            document.getElementById('religion').value = testData.religion;
            document.getElementById('civil_status').value = testData.civil_status;
            document.getElementById('citizenship').value = testData.citizenship;

            document.getElementById('address').value = testData.address;
            document.getElementById('email').value = testData.email;
            document.getElementById('contact_number').value = testData.contact_number;

            document.getElementById('type').value = testData.type;

            console.log("Fields filled with test data!");
        }
    </script>

</x-layout>