"""
Application Entry Point

Run the Flask application directly (development) or via Gunicorn (production).
"""

import os
from app import create_app
from app.config import get_config

# Get configuration based on environment
config_class = get_config()

# Create Flask application
app = create_app(config_class)

# Initialize app with configuration
config_class.init_app(app)

if __name__ == '__main__':
    # Development server (use Gunicorn in production)
    host = os.environ.get('FLASK_HOST', '0.0.0.0')
    port = int(os.environ.get('FLASK_PORT', 5000))
    debug = os.environ.get('FLASK_DEBUG', 'False').lower() == 'true'
    
    app.run(host=host, port=port, debug=debug)
