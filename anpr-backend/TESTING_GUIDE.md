# Testing Guide: Flask-Laravel API Connection

This guide shows you how to test the connection between Flask and Laravel using Postman, curl, or simple test scripts.

## Prerequisites

1. Flask backend running: `python run.py` (should be on `http://localhost:5000`)
2. Laravel backend running: `php artisan serve` (should be on `http://localhost:8000`)
3. Postman installed (optional but recommended)

## Method 1: Test Flask Endpoints with Postman

### 1. Test Flask Health Check (No Auth Required)

**Request:**
- Method: `GET`
- URL: `http://localhost:5000/api/anpr/status`
- Headers: None required

**Expected Response:**
```json
{
    "success": true,
    "status": "operational",
    "version": "1.0.0",
    "timestamp": "2025-01-15T10:30:00Z",
    "services": {
        "anpr_processing": true,
        "image_processing": true,
        "camera_service": true
    }
}
```

### 2. Test Flask Image Upload

**Request:**
- Method: `POST`
- URL: `http://localhost:5000/api/anpr/upload`
- Headers:
  - `Content-Type: multipart/form-data`
- Body (form-data):
  - `file`: [Select an image file]
  - `process_immediately`: `true` (optional)
  - `camera_id`: `camera-0` (optional)
  - `gate_location`: `main-gate` (optional)

**Expected Response:**
```json
{
    "success": true,
    "message": "Image uploaded successfully",
    "image_path": "storage/uploads/2025/01/15/uuid.jpg",
    "process_immediately": true,
    "processing_result": {
        "plate_number": "ABC-1234",
        "confidence": 0.95,
        "vehicle_detected": true,
        "plate_detected": true
    }
}
```

### 3. Test Flask Process Endpoint

**Request:**
- Method: `POST`
- URL: `http://localhost:5000/api/anpr/process`
- Headers:
  - `Content-Type: multipart/form-data`
- Body (form-data):
  - `file`: [Select an image file]
  - `camera_id`: `camera-0`
  - `gate_location`: `main-gate`
  - `direction`: `entry`

**Expected Response:**
```json
{
    "success": true,
    "processing_time_ms": 1250,
    "results": {
        "plate_number": "ABC-1234",
        "normalized_plate": "ABC1234",
        "confidence": 0.95,
        "vehicle_detected": true,
        "plate_detected": true,
        "is_authorized": false
    },
    "metadata": {
        "image_path": "storage/uploads/...",
        "camera_id": "camera-0",
        "gate_location": "main-gate",
        "direction": "entry"
    }
}
```

## Method 2: Test with curl (Command Line)

### Test Flask Status
```bash
curl http://localhost:5000/api/anpr/status
```

### Test Flask Upload
```bash
curl -X POST http://localhost:5000/api/anpr/upload \
  -F "file=@/path/to/your/image.jpg" \
  -F "process_immediately=true"
```

### Test Flask Process
```bash
curl -X POST http://localhost:5000/api/anpr/process \
  -F "file=@/path/to/your/image.jpg" \
  -F "camera_id=camera-0" \
  -F "gate_location=main-gate" \
  -F "direction=entry"
```

## Method 3: Test with Python Script

See `test_flask_api.py` in the project root for a complete test script.

## Method 4: Test Laravel → Flask Connection

Once Laravel integration is implemented, you can test from Laravel:

### Using Laravel Tinker
```php
php artisan tinker

// Test Flask connection
$response = Http::post('http://localhost:5000/api/anpr/status');
$response->json();
```

### Using Postman to Test Laravel Endpoints

**Request:**
- Method: `POST`
- URL: `http://localhost:8000/api/anpr/process`
- Headers:
  - `Authorization: Bearer {your-sanctum-token}`
  - `Content-Type: multipart/form-data`
- Body:
  - `file`: [Select image]

## Testing API Key Authentication

When API key authentication is implemented, add this header to all requests:

```
X-API-Key: your-api-key-here
```

## Troubleshooting

### Flask not responding?
1. Check if Flask is running: `python run.py`
2. Check the port: Should be `http://localhost:5000`
3. Check Flask logs for errors

### CORS errors?
- Flask CORS is configured to allow all origins in development
- Check `app/__init__.py` for CORS settings

### Connection refused?
- Make sure Flask is running before testing
- Check firewall settings
- Verify port 5000 is not blocked

## Next Steps

1. Test basic Flask endpoints (status, upload, process)
2. Implement API key authentication
3. Test Laravel → Flask communication
4. Test Flask → Laravel callback (when implemented)
