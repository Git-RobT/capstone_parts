<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function validatePhoneInput() {
            const phoneInput = document.getElementById('phone');
            const phonePattern = /^\+63\d{10}$/; // Enforces +63 format with exactly 10 digits

            if (!phonePattern.test(phoneInput.value)) {
                alert('Please enter a valid Philippine phone number (e.g., +639XXXXXXXXX).');
                return false; // Prevents form submission if invalid
            }
            return true;
        }
    </script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold text-center mb-4">OTP Verification</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('otp.send') }}" method="POST" class="mb-4" onsubmit="return validatePhoneInput()">
            @csrf
            <label for="phone" class="block text-sm font-medium">Phone Number (Philippines Only)</label>
            <input type="text" name="phone" id="phone" class="w-full border rounded p-2 mb-2"
                placeholder="+639XXXXXXXXX" required pattern="^\+63\d{10}$"
                title="Enter a valid phone number in the format +639XXXXXXXXX">
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
                Send OTP
            </button>
        </form>

        <form action="{{ route('otp.verify') }}" method="POST">
            @csrf
            <label for="otp" class="block text-sm font-medium">Enter OTP</label>
            <input type="text" name="otp" id="otp" class="w-full border rounded p-2 mb-2" required pattern="\d{6}"
                title="Enter a 6-digit OTP">
            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">
                Verify OTP
            </button>
        </form>
    </div>
</body>

</html>