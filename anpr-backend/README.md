# ANPR Flask Backend Service

Production-ready Flask backend for Automatic Number Plate Recognition (ANPR) system.

## Architecture

This service provides REST API endpoints for ANPR processing, camera management, and system health monitoring. The architecture follows a clean separation of concerns:

- **API Layer**: Flask Blueprints for route definitions
- **Service Layer**: Business logic and ANPR processing
- **Model Layer**: ML models and detection engines (to be implemented)
- **Utils Layer**: Helper functions and utilities

## Project Structure

```
anpr-backend/
├── app/
│   ├── __init__.py              # Flask app factory
│   ├── config.py                 # Configuration management
│   ├── api/                      # API routes (Blueprints)
│   │   ├── __init__.py
│   │   ├── anpr.py              # ANPR processing endpoints
│   │   └── cameras.py           # Camera management endpoints
│   ├── services/                 # Business logic layer
│   │   ├── __init__.py
│   │   ├── anpr_service.py      # ANPR processing service
│   │   ├── camera_service.py    # Camera management service
│   │   └── image_service.py     # Image processing utilities
│   ├── models/                   # ML models and detection (to be implemented)
│   │   ├── __init__.py
│   │   └── detectors.py         # Placeholder for YOLO/OCR models
│   └── utils/                    # Utility functions
│       ├── __init__.py
│       ├── logger.py            # Logging configuration
│       └── validators.py         # Input validation helpers
├── storage/                      # Temporary file storage
│   ├── uploads/                  # Uploaded images
│   └── processed/               # Processed images
├── logs/                         # Application logs
├── requirements.txt              # Python dependencies
├── Dockerfile                    # Docker container definition
├── docker-compose.yml            # Docker Compose configuration
├── .env.example                  # Environment variables template
├── .dockerignore                 # Docker ignore file
└── run.py                        # Application entry point
```

## Environment Variables

Copy `.env.example` to `.env` and configure:

```bash
FLASK_APP=run.py
FLASK_ENV=production
FLASK_DEBUG=False
SECRET_KEY=your-secret-key-here
ANPR_API_KEY=your-api-key-for-laravel-auth
LARAVEL_API_URL=http://laravel-app:8000
LOG_LEVEL=INFO
MAX_UPLOAD_SIZE=10485760  # 10MB in bytes
```

## Running the Service

### Development Mode

```bash
python -m venv venv
source venv/bin/activate  # On Windows: venv\Scripts\activate
pip install -r requirements.txt
python run.py
```

### Docker

```bash
docker-compose up -d
```

### Production

```bash
docker build -t anpr-backend .
docker run -d -p 5000:5000 --env-file .env anpr-backend
```

## API Endpoints

### Health & Status

- `GET /api/anpr/status` - System health check

### ANPR Processing

- `POST /api/anpr/process` - Process image for ANPR recognition
- `POST /api/anpr/upload` - Upload and process image file

### Camera Management

- `GET /api/anpr/cameras` - List available cameras
- `POST /api/anpr/cameras/{id}/start` - Start camera stream (future)
- `POST /api/anpr/cameras/{id}/stop` - Stop camera stream (future)

## Development

### Adding New Endpoints

1. Create route in `app/api/` (use Blueprint)
2. Implement business logic in `app/services/`
3. Register Blueprint in `app/__init__.py`

### Adding ML Models

1. Place model files in `app/models/`
2. Implement detection logic in `app/services/anpr_service.py`
3. Load models at application startup

## License

Proprietary - CLSU ANPR System
