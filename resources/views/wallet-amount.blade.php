<!DOCTYPE html>
<html>
<head>
    <title>Binance Wallet Amount</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
</head>
<body style="font-family: 'Nunito', sans-serif;" class="p-10">
    <h1 class="text-2xl font-bold mb-6">Binance Wallet Amount</h1>
    <table class="w-full border text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="p-3">Asset</th>
                <th scope="col" class="p-3">Estimated Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($wallet_amounts as $symbol => $estimated_balance)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="p-4">{{ $symbol }}</td>
                    <td class="p-4">{{ $estimated_balance }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
