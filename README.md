## Brandgage Lead Retrieval System

# Example API Responses

The response structure is standarized and follows a specific convention.

## Success Responses

### - Fetching A Resource Collection Response Example:

```json
{
    "data": [
        {
            "object_type": "attendee",
            "id": 1,
            "name": "John Doe"
        },
        {
            "object_type": "attendee",
            "id": 2,
            "name": "Jane Doe"
        }
    ],
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/attendees?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/attendees",
        "per_page": 15,
        "to": 6,
        "total": 6
    },
    "links": {
        "first": "http://127.0.0.1:8000/api/attendees?page=1",
        "last": "http://127.0.0.1:8000/api/attendees?page=1",
        "prev": null,
        "next": null
    }
}
```

### - Fetching A single Resource Response Example:

```json
{
    "data": {
        "object_type": "attendee",
        "id": 1,
        "name": "John Doe",
        "some_prop": "etc..."
    }
}
```

## Success Responses

### - Resource Deleted Successfully Response Example:

```json
{
    "success": {
        "message": "Resource Deleted",
        "data": {
            "id": 1
        }
    }
}
```

### - Resource Created Successfully Response Example:

```json
{
    "success": {
        "message": "Resource created",
        "data": {
            "object_type": "event",
            "id": 1,
            "some_prop": "value"
        }
    }
}
```

## Error Responses

### - Resource Not Found Error Response Example:

```json
{
    "error": {
        "message": "Not Found",
        "data": null
    }
}
```

### - Validation Faild Error Response Example:

```json
{
    "error": {
        "message": "Invalid Inputs",
        "data": {
            "name": "Name field is required",
            "email": "Must be a valid email"
        }
    }
}
```
