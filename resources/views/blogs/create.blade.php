<!DOCTYPE html>
<html>
<head>
    <title>Create Blog</title>
</head>
<body>
<h2>Create Blog</h2>
<form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label>Title:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Content:</label><br>
    <textarea name="content" required></textarea><br><br>

    <label>Image:</label><br>
    <input type="file" name="image"><br><br>

    <button type="submit">Save</button>
</form>
<a href="{{ route('blogs.index') }}">⬅ Back</a>
</body>
</html>
