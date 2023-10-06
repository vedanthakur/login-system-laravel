<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <title>Image Upload</title>
    </head>
    <body>
        <div class="container">
            <div class="row">
                @if (count($images) > 0)
                    @foreach($images as $image)
                        <div class="col-md-4">
                            <div class="card bg-white shadow-lg mb-4 border-radius-10">
                                <div class="card-header text-center">
                                    <h5 class="text-align-center font-size-20 font-weight-blod">{{ $image->name }}</h5>
                                </div>
                                <div class="card-body padding-20">
                                    @php 
                                        $photos = explode(',', $image->image_path);
                                    @endphp

                                    @foreach ($photos as $photo)
                                        <img src="/uploads/{{ $photo }}" width="100px" alt="{{ $photo }}">
                                    @endforeach
                                    <form action="{{ route('images.destroy', $image->id) }}" method="post" class="flex-shrink-0 w-50 ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">
                                          <i class="bi bi-trash"></i> Delete
                                        </button>
                                      </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </body>
</html>