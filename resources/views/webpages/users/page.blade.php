<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vue E-commerce</title>
    <!-- Include Vite JS -->
    @vite('resources/js/app.js')
</head>
<body id="app" class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <div class="p-6 text-gray-900">
            {{ __("You're logged in!") }}
            <!-- Vue Component -->
            <div id="apps">
                <template-component></template-component>
            </div>
        </div>
    </div>
</body>
</html>
