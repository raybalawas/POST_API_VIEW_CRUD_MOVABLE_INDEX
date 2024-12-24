<!DOCTYPE html>
<html>

<head>
    <title>Posts</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
        }

        a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }

        a:hover {
            color: #087f23;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background: #fff;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: move;
        }

        h3 {
            margin: 0;
            color: #333;
        }

        p {
            margin: 10px 0;
            line-height: 1.5;
        }

        form {
            display: inline;
        }

        button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #c0392b;
        }

        .create-post {
            display: block;
            width: fit-content;
            margin: 10px auto;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
        }

        .create-post:hover {
            background-color: #087f23;
        }

        .dragging {
            opacity: 0.5;
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <h1>Posts</h1>
    <a href="{{ route('posts.create') }}" class="create-post">Create New Post</a>
    <ul id="posts-list">
        @foreach ($posts as $post)
            <li data-id="{{ $post->id }}">
                <h3>{{ $post->title }}</h3>
                <p>{{ $post->content }}</p>
                <a href="{{ route('posts.edit', $post->id) }}">Edit</a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
    <button id="save-order" style="margin-top: 20px;">Save Order</button>

    <script>
        // Initialize Sortable.js on the #posts-list element
        var sortable = new Sortable(document.getElementById('posts-list'), {
            animation: 150, // Animation speed in milliseconds
            ghostClass: 'dragging', // Class to apply while dragging
        });

        // Save the new order to the server
        $('#save-order').on('click', function() {
            // Get the new order of post IDs from the list
            const order = [];
            // alert(order);

            $('#posts-list li').each(function() {
                order.push($(this).data('id'));
            });

            // Send the new order to the server using AJAX
            $.ajax({
                url: '{{ route('posts.reorder') }}', // Add your reorder route here
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    order: order,
                },
                success: function(response) {
                    if (response.success) {
                        alert('Order saved successfully!');
                    } else {
                        alert('Failed to save order.');
                    }
                },
                error: function() {
                    alert('Error saving the order.');
                }
            });
        });
    </script>
</body>

</html>
