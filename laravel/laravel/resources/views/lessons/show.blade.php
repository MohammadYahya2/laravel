<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lesson->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #4361ee;
            border-color: #4361ee;
        }
        .lesson-content {
            line-height: 1.7;
            white-space: pre-wrap;
        }
        .course-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0">{{ $lesson->title }}</h3>
                <a href="{{ route('lessons.index') }}" class="btn btn-light">Back to List</a>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="text-muted mb-3">Lesson Content:</h5>
                    <div class="lesson-content">{{ $lesson->content }}</div>
                </div>

                <div class="course-info">
                    <h5>Course Information:</h5>
                    <p><strong>Course Name:</strong> {{ $lesson->course->title ?? 'Not specified' }}</p>
                    <p><strong>Course Description:</strong> {{ $lesson->course->description ?? 'Not available' }}</p>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('lessons.edit', $lesson) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('lessons.destroy', $lesson) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this lesson?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
            <div class="card-footer text-muted">
                <div class="row">
                    <div class="col-md-6">
                        Created: {{ $lesson->created_at->format('Y-m-d H:i') }}
                    </div>
                    <div class="col-md-6 text-md-end">
                        Last updated: {{ $lesson->updated_at->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 