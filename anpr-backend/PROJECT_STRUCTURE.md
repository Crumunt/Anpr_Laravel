# ANPR Flask Backend - Project Structure

## Complete Directory Tree

```
anpr-backend/
├── app/                              # Main application package
│   ├── __init__.py                   # Flask app factory
│   ├── config.py                     # Configuration management (dev/prod/test)
│   ├── api/                          # API Blueprints (routes)
│   │   ├── __init__.py
│   │   ├── anpr.py                   # ANPR processing endpoints
│   │   └── cameras.py                # Camera management endpoints
│   ├── services/                     # Business logic layer
│   │   ├── __init__.py
│   │   ├── anpr_service.py           # ANPR processing orchestration
│   │   ├── camera_service.py         # Camera management
│   │   └── image_service.py         # Image handling utilities
│   ├── models/                       # ML models (placeholders)
│   │   ├── __init__.py
│   │   └── detectors.py              # Vehicle/Plate detection & OCR stubs
│   └── utils/                        # Utility functions
│       ├── __init__.py
│       ├── logger.py                 # Logging configuration
│       └── validators.py             # Input validation helpers
├── storage/                          # File storage
│   ├── uploads/                      # Uploaded images
│   │   └── .gitkeep
│   └── processed/                   # Processed images
│       └── .gitkeep
├── logs/                             # Application logs
│   └── .gitkeep
├── requirements.txt                  # Python dependencies (pinned versions)
├── Dockerfile                        # Production Docker container
├── docker-compose.yml               # Docker Compose configuration
├── .env.example                     # Environment variables template
├── .dockerignore                    # Docker ignore patterns
├── run.py                           # Application entry point
├── README.md                        # Project documentation
└── PROJECT_STRUCTURE.md             # This file
```

## Key Files Explained

### Core Application

- **`app/__init__.py`**: Flask application factory using Blueprint pattern
- **`app/config.py`**: Environment-based configuration (dev/prod/test)
- **`run.py`**: Application entry point (development server or Gunicorn)

### API Layer (Blueprints)

- **`app/api/anpr.py`**: 
  - `POST /api/anpr/process` - Process image for ANPR
  - `POST /api/anpr/upload` - Upload and process image
  - `GET /api/anpr/status` - Health check

- **`app/api/cameras.py`**:
  - `GET /api/anpr/cameras` - List available cameras
  - `GET /api/anpr/cameras/<id>/status` - Camera status

### Service Layer

- **`app/services/anpr_service.py`**: Main ANPR processing pipeline
  - Orchestrates: vehicle detection → plate detection → OCR → validation
  - Returns structured recognition results

- **`app/services/image_service.py`**: Image handling
  - Load, save, download images
  - Preprocessing utilities

- **`app/services/camera_service.py`**: Camera management
  - Enumerate USB cameras
  - Check camera status
  - Stream management (placeholders)

### ML Models (Placeholders)

- **`app/models/detectors.py`**: Stub classes for:
  - `VehicleDetector` - YOLO vehicle detection
  - `PlateDetector` - License plate detection
  - `OCREngine` - OCR text extraction

### Utilities

- **`app/utils/logger.py`**: Structured logging with file rotation
- **`app/utils/validators.py`**: Input validation for files, plate numbers

## Architecture Principles

1. **Separation of Concerns**: Routes → Services → Models
2. **Blueprint Pattern**: Modular, scalable route organization
3. **Service Layer**: Business logic separated from API endpoints
4. **Configuration Management**: Environment-based config
5. **Error Handling**: Global error handlers with structured JSON responses
6. **Logging**: Production-ready logging with rotation
7. **Docker Ready**: Containerized deployment with health checks

## Next Steps for ML Integration

When implementing actual ANPR processing:

1. **Add ML dependencies** to `requirements.txt`:
   - `opencv-python==4.8.1.78`
   - `ultralytics==8.0.196` (YOLOv8)
   - `easyocr==1.7.0` or `pytesseract==0.3.10`

2. **Implement model loading** in `app/models/detectors.py`

3. **Update service methods** in `app/services/anpr_service.py`:
   - Replace mock detection with actual YOLO inference
   - Replace mock OCR with EasyOCR/Tesseract

4. **Uncomment OpenCV dependencies** in `Dockerfile`

## Environment Variables

See `.env.example` for all configuration options:
- Flask settings (host, port, debug)
- Security (API keys, secret key)
- Laravel integration URL
- File upload limits
- Camera settings
- ANPR processing thresholds

## Running the Application

### Development
```bash
python -m venv venv
source venv/bin/activate  # Windows: venv\Scripts\activate
pip install -r requirements.txt
python run.py
```

### Docker
```bash
docker-compose up -d
```

### Production
```bash
gunicorn --bind 0.0.0.0:5000 --workers 4 run:app
```

## API Documentation

All endpoints return JSON with consistent structure:
```json
{
    "success": true/false,
    "error": "Error type (if failed)",
    "message": "Human-readable message",
    "data": { ... }
}
```

See individual endpoint docstrings in `app/api/*.py` for detailed request/response formats.
