"""
Input Validation Utilities

Helper functions for validating API inputs, file uploads, and data formats.
"""

from flask import current_app
from werkzeug.utils import secure_filename


def allowed_file(filename):
    """
    Check if file extension is allowed.
    
    Args:
        filename: Name of the uploaded file
        
    Returns:
        bool: True if file extension is allowed, False otherwise
    """
    if '.' not in filename:
        return False
    
    extension = filename.rsplit('.', 1)[1].lower()
    return extension in current_app.config['ALLOWED_EXTENSIONS']


def validate_image_file(file):
    """
    Validate uploaded image file.
    
    Checks:
    - File exists and has content
    - File extension is allowed
    - File size is within limits
    
    Args:
        file: FileStorage object from Flask request
        
    Returns:
        tuple: (is_valid: bool, error_message: str or None)
    """
    if not file:
        return False, "No file provided"
    
    if file.filename == '':
        return False, "No file selected"
    
    if not allowed_file(file.filename):
        allowed = ', '.join(current_app.config['ALLOWED_EXTENSIONS'])
        return False, f"File type not allowed. Allowed types: {allowed}"
    
    # Check file size
    file.seek(0, 2)  # Seek to end
    file_size = file.tell()
    file.seek(0)  # Reset to beginning
    
    max_size = current_app.config.get('MAX_CONTENT_LENGTH', 10485760)
    if file_size > max_size:
        max_mb = max_size / (1024 * 1024)
        return False, f"File size exceeds maximum allowed size of {max_mb:.1f}MB"
    
    return True, None


def get_secure_filename(filename):
    """
    Get secure filename for storage.
    
    Args:
        filename: Original filename
        
    Returns:
        str: Secure filename safe for filesystem storage
    """
    return secure_filename(filename)


def validate_plate_number(plate_number):
    """
    Validate license plate number format.
    
    This is a basic validation - can be enhanced based on specific
    plate format requirements (e.g., Philippine format: ABC-1234).
    
    Args:
        plate_number: License plate string to validate
        
    Returns:
        tuple: (is_valid: bool, normalized_plate: str, error_message: str or None)
    """
    if not plate_number:
        return False, None, "Plate number is required"
    
    # Remove spaces and convert to uppercase
    normalized = plate_number.replace(' ', '').replace('-', '').upper()
    
    # Basic validation: alphanumeric, reasonable length
    if not normalized.isalnum():
        return False, None, "Plate number must contain only alphanumeric characters"
    
    if len(normalized) < 3 or len(normalized) > 10:
        return False, None, "Plate number length must be between 3 and 10 characters"
    
    return True, normalized, None
