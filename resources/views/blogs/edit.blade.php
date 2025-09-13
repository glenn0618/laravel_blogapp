<!DOCTYPE html>
<html>
<head>
    <title>Edit Blog</title>
</head>
<body>
<h2>Edit Blog</h2>
<form action="{{ route('blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label>Title:</label><br>
    <input type="text" name="title" value="{{ $blog->title }}" required><br><br>

    <label>Content:</label><br>
    <textarea name="content" required>{{ $blog->content }}</textarea><br><br>

    <label>Image:</label><br>
    @if($blog->image)
        <img src="{{ asset('uploads/' . $blog->image) }}" width="150"><br>
    @endif
    <input type="file" name="image"><br><br>

    <button type="submit">Update</button>
</form>
<a href="{{ route('blogs.index') }}">⬅ Back</a>
</body>
</html>
