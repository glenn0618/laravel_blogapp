<!DOCTYPE html>
<html>
<head>
    <title>Blog App</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; }
        .container { width: 80%; margin: 40px auto; }
        .btn { padding: 10px 15px; background: #007bff; color: #fff; text-decoration: none; border-radius: 6px; }
        .btn:hover { background: #0056b3; }
        .post { background: #fff; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        img { max-width: 300px; margin-top: 10px; border-radius: 6px; }
    </style>
</head>
<body>
<div class="container">
    <h2>📝 Blog App</h2>
    <a href="{{ route('blogs.create') }}" class="btn">+ Create New Blog</a>
    <hr>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @foreach($blogs as $blog)
        <div class="post">
            <h3>{{ $blog->title }}</h3>
            <p>{{ $blog->content }}</p>
            @if($blog->image)
                <img src="{{ asset('uploads/' . $blog->image) }}" alt="Blog Image">
            @endif
            <br>
            <a href="{{ route('blogs.edit', $blog->id) }}" class="btn">✏ Edit</a>
            <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn" style="background:red;" onclick="return confirm('Are you sure?')">🗑 Delete</button>
            </form>
        </div>
    @endforeach
</div>
</body>
</html>
