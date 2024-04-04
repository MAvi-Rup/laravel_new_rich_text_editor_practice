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
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans antialias bg-red-400">
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl text-gray-50 mb-4">All Posts</h1>
        <!-- Form to add new post -->
        <form class="mb-4">
            @csrf
            <div id="editor" class="w-full bg-gray-100 rounded-lg p-4" style="min-height: 150px;"></div>
            <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Add
                Post</button>
        </form>
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
        // Initialize Quill editor
        const quillEditor = new Quill('#editor', {
            theme: 'snow',
            modules: {
                imageResize: {
                    modules: ['Resize', 'DisplaySize']
                },
                toolbar: {
                    container: [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        ['link', 'image', 'video', 'formula'],
                        [{
                            'header': 1
                        }, {
                            'header': 2
                        }],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }, {
                            'list': 'check'
                        }],
                        [{
                            'script': 'sub'
                        }, {
                            'script': 'super'
                        }],
                        [{
                            'indent': '-1'
                        }, {
                            'indent': '+1'
                        }],
                        [{
                            'direction': 'rtl'
                        }],
                        [{
                            'size': ['small', false, 'large', 'huge']
                        }],
                        [{
                            'header': [1, 2, 3, 4, 5, 6, false]
                        }],
                        [{
                            'color': []
                        }, {
                            'background': []
                        }],
                        [{
                            'font': []
                        }],
                        [{
                            'align': []
                        }],
                        ['clean']
                    ],
                    handlers: {
                        image: imageHandler
                    }
                }
            }
        });

        // Set the toolbar background color to orange
        const toolbarEl = quillEditor.root.previousSibling.childNodes[0].childNodes[0];
        toolbarEl.style.backgroundColor = 'orange';

        // Image upload handler
        function imageHandler() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = async () => {
                const files = input.files;
                const range = quillEditor.getSelection();
                if (!files || !files.length) {
                    console.log('No files selected');
                    return;
                }

                // Upload images to server
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const formData = new FormData();
                    formData.append('image', file);

                    try {
                        const response = await fetch('/upload-image', {
                            method: 'POST',
                            body: formData
                        });

                        if (response.ok) {
                            const imageUrl = await response.text();
                            quillEditor.insertEmbed(range.index, 'image', imageUrl);
                        } else {
                            console.error('Error uploading image:', response.statusText);
                        }
                    } catch (error) {
                        console.error('Error uploading image:', error);
                    }
                }
            };
        }
    </script>
</body>

</html>
