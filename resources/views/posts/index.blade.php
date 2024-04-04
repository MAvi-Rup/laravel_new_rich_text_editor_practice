<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Quill Editor</title>
    <!-- Include the Quill styles -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- Include the Quill JavaScript -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <!-- Include Quill Image Resize module -->
    <script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdn.ckeditor.com/4.20.1/standard-all/plugins/imageresizerowandcolumn/plugin.js"></script>
    <!-- Tailwind CSS -->
    <meta name="viewport" content="width=device-width, initial-scale=1" @vite('resources/css/app.css')>
</head>

<body class="font-sans antialias bg-red-400">
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl text-gray-50 mb-4">All Posts</h1>
        <textarea name="editor1" id="editor1" rows="10" cols="80">
            
        </textarea>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach ($posts as $post)
                <div class="relative bg-white shadow-md rounded-lg p-6">
                    <p class="text-xl font-semibold mb-4">{{ $post->title }}</p>
                    <div>{!! $post->body !!}</div>
                    <button onclick="editPost({{ $post->id }})"
                        class="absolute top-0 right-0 mt-2 mr-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Edit</button>
                </div>
            @endforeach
        </div>
    </div>
    <div class="mt-8">
        {{ $posts->onEachSide(5)->links() }}
    </div>

    <script>
        CKEDITOR.replace('editor1', {
            height: 200,
            filebrowserUploadUrl: "upload.php"

        });
    </script>
</body>

</html>
