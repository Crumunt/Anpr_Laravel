"""
Configuration Management

Handles application configuration for different environments (development, production, testing).
Uses environment variables with sensible defaults.
"""

import os
from pathlib import Path

from dotenv import load_dotenv

# Load environment variables from .env file
load_dotenv()


class Config:
    """
    Base configuration class with common settings.
    """
    
    # Flask Configuration
    SECRET_KEY = os.environ.get('SECRET_KEY') or 'dev-secret-key-change-in-production'
    DEBUG = os.environ.get('FLASK_DEBUG', 'False').lower() == 'true'
    TESTING = False
    
    # API Configuration
    ANPR_API_KEY = os.environ.get('ANPR_API_KEY') or ''
    LARAVEL_API_URL = os.environ.get('LARAVEL_API_URL') or 'http://localhost:8000'
    LARAVEL_API_KEY = os.environ.get('LARAVEL_API_KEY') or ''
    
    # File Upload Configuration
    MAX_CONTENT_LENGTH = int(os.environ.get('MAX_UPLOAD_SIZE', 10485760))  # 10MB default
    UPLOAD_FOLDER = os.environ.get('UPLOAD_FOLDER') or 'storage/uploads'
    PROCESSED_FOLDER = os.environ.get('PROCESSED_FOLDER') or 'storage/processed'
    ALLOWED_EXTENSIONS = set(os.environ.get('ALLOWED_EXTENSIONS', 'jpg,jpeg,png').split(','))
    
    # Camera Configuration
    DEFAULT_CAMERA_INDEX = int(os.environ.get('DEFAULT_CAMERA_INDEX', 0))
    CAMERA_FPS = int(os.environ.get('CAMERA_FPS', 2))
    CAMERA_RESOLUTION_WIDTH = int(os.environ.get('CAMERA_RESOLUTION_WIDTH', 1280))
    CAMERA_RESOLUTION_HEIGHT = int(os.environ.get('CAMERA_RESOLUTION_HEIGHT', 720))
    
    # ANPR Processing Configuration
    ANPR_CONFIDENCE_THRESHOLD = float(os.environ.get('ANPR_CONFIDENCE_THRESHOLD', 0.5))
    ANPR_PROCESSING_TIMEOUT = int(os.environ.get('ANPR_PROCESSING_TIMEOUT', 30))
    
    # CORS Configuration
    CORS_ORIGINS = os.environ.get('CORS_ORIGINS', '*').split(',')
    
    # Logging Configuration
    LOG_LEVEL = os.environ.get('LOG_LEVEL', 'INFO')
    LOG_FILE = os.environ.get('LOG_FILE') or 'logs/anpr-backend.log'
    
    # Ensure directories exist
    @staticmethod
    def init_app(app):
        """
        Initialize application with configuration.
        Creates necessary directories if they don't exist.
        
        Args:
            app: Flask application instance
        """
        # Create storage directories
        for folder in [Config.UPLOAD_FOLDER, Config.PROCESSED_FOLDER, 'logs']:
            Path(folder).mkdir(parents=True, exist_ok=True)


class DevelopmentConfig(Config):
    """
    Development environment configuration.
    """
    DEBUG = True
    LOG_LEVEL = 'DEBUG'


class ProductionConfig(Config):
    """
    Production environment configuration.
    """
    DEBUG = False
    LOG_LEVEL = 'INFO'
    
    # Production-specific settings
    # Consider using Redis for caching, PostgreSQL for data, etc.


class TestingConfig(Config):
    """
    Testing environment configuration.
    """
    TESTING = True
    DEBUG = True
    # Use in-memory storage for tests
    UPLOAD_FOLDER = 'storage/test_uploads'
    PROCESSED_FOLDER = 'storage/test_processed'


# Configuration dictionary
config = {
    'development': DevelopmentConfig,
    'production': ProductionConfig,
    'testing': TestingConfig,
    'default': DevelopmentConfig
}


def get_config():
    """
    Get configuration class based on environment variable.
    
    Returns:
        Config: Configuration class instance
    """
    env = os.environ.get('FLASK_ENV', 'development')
    return config.get(env, config['default'])
