"""
Flask Application Factory

This module creates and configures the Flask application instance.
Uses the application factory pattern for better testability and configuration management.
"""

from flask import Flask
from flask_cors import CORS

from app.config import Config
from app.utils.logger import setup_logging


def create_app(config_class=Config):
    """
    Application factory function.
    
    Creates and configures a Flask application instance.
    
    Args:
        config_class: Configuration class to use (default: Config)
        
    Returns:
        Flask: Configured Flask application instance
    """
    app = Flask(__name__)
    app.config.from_object(config_class)
    
    # Initialize CORS - allow cross-origin requests from Laravel frontend
    CORS(app, resources={
        r"/api/*": {
            "origins": app.config.get('CORS_ORIGINS', '*'),
            "methods": ["GET", "POST", "PUT", "DELETE", "OPTIONS"],
            "allow_headers": ["Content-Type", "Authorization", "X-API-Key"]
        }
    })
    
    # Setup logging
    setup_logging(app)
    
    # Register Blueprints
    from app.api.anpr import anpr_bp
    from app.api.cameras import cameras_bp
    
    app.register_blueprint(anpr_bp, url_prefix='/api/anpr')
    app.register_blueprint(cameras_bp, url_prefix='/api/anpr/cameras')
    
    # Register error handlers
    register_error_handlers(app)
    
    # Log application startup
    app.logger.info("ANPR Flask Backend initialized successfully")
    app.logger.info(f"Environment: {app.config.get('ENV', 'production')}")
    
    return app


def register_error_handlers(app):
    """
    Register global error handlers for the application.
    
    Args:
        app: Flask application instance
    """
    from flask import jsonify
    
    @app.errorhandler(400)
    def bad_request(error):
        """Handle 400 Bad Request errors."""
        return jsonify({
            'success': False,
            'error': 'Bad Request',
            'message': str(error.description) if hasattr(error, 'description') else 'Invalid request parameters'
        }), 400
    
    @app.errorhandler(404)
    def not_found(error):
        """Handle 404 Not Found errors."""
        return jsonify({
            'success': False,
            'error': 'Not Found',
            'message': 'The requested resource was not found'
        }), 404
    
    @app.errorhandler(500)
    def internal_error(error):
        """Handle 500 Internal Server errors."""
        app.logger.error(f"Internal Server Error: {str(error)}")
        return jsonify({
            'success': False,
            'error': 'Internal Server Error',
            'message': 'An unexpected error occurred'
        }), 500
    
    @app.errorhandler(413)
    def request_entity_too_large(error):
        """Handle 413 Request Entity Too Large errors."""
        return jsonify({
            'success': False,
            'error': 'Request Entity Too Large',
            'message': f'File size exceeds maximum allowed size of {app.config.get("MAX_CONTENT_LENGTH", 0)} bytes'
        }), 413
