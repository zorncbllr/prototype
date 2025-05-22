<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/tailwind.css">
</head>

<body class="flex flex-col items-center bg-gray-100">
    <p class="text-xl text-gray-900 py-4 mt-4">Manage Voters</p>


    <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-4/6">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500  ">
            <div class="p-5 text-lg font-semibold text-left text-gray-900 bg-white flex justify-between w-full">
                <span>Registered Voters</span>

                <div class="inline-flex rounded-md shadow-xs" role="group">
                    <form action="/import" method="post" class="relative" id="import-form" enctype="multipart/form-data">
                        <div class="relative">
                            <input
                                type="file"
                                id="pdf-upload"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                name="pdf"
                                accept="application/pdf" />
                            <label
                                for="pdf-upload"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                                <span class="text-sm font-medium text-gray-700">Import</span>
                            </label>
                        </div>
                    </form>

                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700">
                        Export
                    </button>
                </div>
            </div>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50    ">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Voter ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Precinct
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Address
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <span class="sr-only"></span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($voters as $voter): ?>
                    <tr class="bg-white border-b  border-gray-200">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            <?= $voter->voterId ?>
                        </th>
                        <td class="px-6 py-4">
                            <?= $voter->name ?>
                        </td>
                        <td class="px-6 py-4">
                            <?= $voter->precinct ?>
                        </td>
                        <td class="px-6 py-4">
                            <?= $voter->address ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="#" class="font-medium text-blue-600   hover:underline">Mark</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <script type="module" src="js/script.js"></script>
</body>

</html>