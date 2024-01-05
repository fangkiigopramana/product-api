<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test Case ADS Digital Partner | Magang MSIB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="container my-lg-5">
    <section>
        <div class="mb-3">
            <h3><strong>Tabel Category</strong></h3>
        </div>
        <div class="container table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Name</th>
                        <th scope="col">Total Product</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->products_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section>
        <div class="mb-3">
            <h3><strong>Tabel Product</strong></h3>
        </div>
        <div class="container table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Name</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Price</th>
                        <th scope="col">Assets</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td rowspan="{{ count($product->assets) + 1 }}">{{ $loop->iteration }}</td>
                            <td rowspan="{{ count($product->assets) + 1 }}">{{ $product->name }}</td>
                            <td rowspan="{{ count($product->assets) + 1 }}">{{ $product->slug }}</td>
                            <td rowspan="{{ count($product->assets) + 1 }}">{{ number_format($product->price, 0, ',', '.') }}</td>
                        </tr>
                            @foreach ($product->assets as $index => $asset)
                                <tr>
                                    @if ($index === 0)
                                        <td>{{ $asset->image }}</td>
                                    @else
                                        <td>{{ $asset->image }}</td>
                                    @endif
                                </tr>
                            @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</body>

</html>
