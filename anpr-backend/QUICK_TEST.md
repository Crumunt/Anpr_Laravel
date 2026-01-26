# Quick Test Guide

## Step 1: Start Flask Backend

```bash
cd anpr-backend
python run.py
```

You should see:
```
 * Running on http://127.0.0.1:5000
```

## Step 2: Test with Browser (Easiest!)

Just open your browser and go to:
```
http://localhost:5000/api/anpr/status
```

You should see JSON response like:
```json
{
    "success": true,
    "status": "operational",
    "version": "1.0.0",
    ...
}
```

## Step 3: Test with Postman

1. **Import Collection:**
   - Open Postman
   - Click "Import"
   - Select `ANPR_API.postman_collection.json`

2. **Test Status:**
   - Select "Health Check" request
   - Click "Send"
   - Should get 200 OK response

3. **Test Upload:**
   - Select "Upload Image" request
   - In Body tab, click "Select Files" next to "file"
   - Choose any image (jpg, png)
   - Click "Send"
   - Should get response with processing results

## Step 4: Test with Python Script

```bash
# Basic test (no image)
python test_flask_api.py

# Test with image
python test_flask_api.py path/to/your/image.jpg
```

## Step 5: Test with curl (Windows PowerShell)

```powershell
# Test status
Invoke-WebRequest -Uri "http://localhost:5000/api/anpr/status" -Method GET

# Test upload (if you have an image)
$filePath = "C:\path\to\image.jpg"
$form = @{
    file = Get-Item $filePath
    process_immediately = "true"
}
Invoke-WebRequest -Uri "http://localhost:5000/api/anpr/upload" -Method POST -Form $form
```

## What to Expect

### ✅ Success Response:
- Status code: 200
- JSON with `"success": true`
- Data in response body

### ❌ Error Responses:
- **404**: Endpoint not found (check URL)
- **500**: Server error (check Flask logs)
- **Connection refused**: Flask not running

## Testing API Keys (When Implemented)

Add this header in Postman:
```
Key: X-API-Key
Value: your-api-key-here
```

## Next: Test Laravel Connection

Once Flask is working, you can test from Laravel using the test script or create a simple route.
