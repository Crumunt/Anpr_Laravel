"""
ANPR Processing API Endpoints

Handles image upload, processing requests, and status checks for ANPR operations.
"""

import time
from flask import Blueprint, request, jsonify, current_app
from werkzeug.exceptions import BadRequest

from app.services.anpr_service import AnprService
from app.services.image_service import ImageService
from app.utils.validators import validate_image_file, get_secure_filename

# Create Blueprint
anpr_bp = Blueprint('anpr', __name__)


@anpr_bp.route('/status', methods=['GET'])
def get_status():
    """
    Health check and system status endpoint.
    
    Returns:
        JSON response with system status, version, and health information
        
    Example Response:
        {
            "success": true,
            "status": "operational",
            "version": "1.0.0",
            "timestamp": "2025-01-15T10:30:00Z"
        }
    """
    try:
        # Check if ANPR service is available
        anpr_service = AnprService()
        service_status = anpr_service.check_service_health()
        
        return jsonify({
            'success': True,
            'status': 'operational' if service_status else 'degraded',
            'version': '1.0.0',
            'timestamp': time.strftime('%Y-%m-%dT%H:%M:%SZ', time.gmtime()),
            'services': {
                'anpr_processing': service_status,
                'image_processing': True,
                'camera_service': True
            }
        }), 200
    
    except Exception as e:
        current_app.logger.error(f"Status check failed: {str(e)}")
        return jsonify({
            'success': False,
            'status': 'error',
            'message': 'Unable to determine system status'
        }), 500


@anpr_bp.route('/process', methods=['POST'])
def process_image():
    """
    Process an image for ANPR recognition.
    
    Accepts either:
    - Image file upload (multipart/form-data)
    - Image reference/URL (JSON)
    - Camera stream reference (JSON)
    
    Request Body (multipart/form-data):
        - file: Image file (required if no image_url)
        - camera_id: Camera identifier (optional)
        - gate_location: Gate location identifier (optional)
        - direction: 'entry' or 'exit' (optional)
        
    Request Body (JSON):
        {
            "image_url": "http://...",
            "camera_id": "camera-1",
            "gate_location": "main-gate",
            "direction": "entry"
        }
    
    Returns:
        JSON response with recognition results
        
    Example Response:
        {
            "success": true,
            "processing_time_ms": 1250,
            "results": {
                "plate_number": "ABC-1234",
                "confidence": 0.95,
                "vehicle_detected": true,
                "plate_detected": true,
                "is_authorized": false
            },
            "metadata": {
                "image_path": "storage/processed/...",
                "detected_at": "2025-01-15T10:30:00Z"
            }
        }
    """
    start_time = time.time()
    
    try:
        # Get request data
        camera_id = request.form.get('camera_id') or request.json.get('camera_id')
        gate_location = request.form.get('gate_location') or request.json.get('gate_location', 'unknown')
        direction = request.form.get('direction') or request.json.get('direction', 'entry')
        image_url = request.json.get('image_url') if request.is_json else None
        
        # Initialize services
        image_service = ImageService()
        anpr_service = AnprService()
        
        # Handle different input types
        if 'file' in request.files:
            # File upload
            file = request.files['file']
            is_valid, error_msg = validate_image_file(file)
            
            if not is_valid:
                return jsonify({
                    'success': False,
                    'error': 'Validation Error',
                    'message': error_msg
                }), 400
            
            # Save uploaded file
            filename = get_secure_filename(file.filename)
            image_path = image_service.save_uploaded_image(file, filename)
            
        elif image_url:
            # Image URL provided
            image_path = image_service.download_image_from_url(image_url)
            
        else:
            return jsonify({
                'success': False,
                'error': 'Bad Request',
                'message': 'Either file upload or image_url must be provided'
            }), 400
        
        # Process image for ANPR recognition
        current_app.logger.info(f"Processing ANPR for image: {image_path}, camera: {camera_id}")
        
        recognition_result = anpr_service.process_image(
            image_path=image_path,
            camera_id=camera_id,
            gate_location=gate_location,
            direction=direction
        )
        
        # Calculate processing time
        processing_time = int((time.time() - start_time) * 1000)
        
        return jsonify({
            'success': True,
            'processing_time_ms': processing_time,
            'results': recognition_result,
            'metadata': {
                'image_path': image_path,
                'camera_id': camera_id,
                'gate_location': gate_location,
                'direction': direction,
                'detected_at': time.strftime('%Y-%m-%dT%H:%M:%SZ', time.gmtime())
            }
        }), 200
    
    except ValueError as e:
        current_app.logger.warning(f"Validation error in process_image: {str(e)}")
        return jsonify({
            'success': False,
            'error': 'Validation Error',
            'message': str(e)
        }), 400
    
    except Exception as e:
        current_app.logger.error(f"Error processing image: {str(e)}", exc_info=True)
        return jsonify({
            'success': False,
            'error': 'Processing Error',
            'message': 'An error occurred while processing the image'
        }), 500


@anpr_bp.route('/upload', methods=['POST'])
def upload_image():
    """
    Upload an image file for ANPR processing.
    
    This endpoint accepts an image file, saves it, and optionally
    triggers immediate processing.
    
    Request Body (multipart/form-data):
        - file: Image file (required)
        - process_immediately: Boolean, whether to process immediately (default: true)
        - camera_id: Camera identifier (optional)
        - gate_location: Gate location (optional)
        
    Returns:
        JSON response with upload confirmation and optionally processing results
        
    Example Response:
        {
            "success": true,
            "message": "Image uploaded successfully",
            "image_id": "uuid-here",
            "image_path": "storage/uploads/...",
            "process_immediately": true,
            "processing_result": { ... }  // if process_immediately=true
        }
    """
    try:
        # Check if file is provided
        if 'file' not in request.files:
            return jsonify({
                'success': False,
                'error': 'Bad Request',
                'message': 'No file provided in request'
            }), 400
        
        file = request.files['file']
        is_valid, error_msg = validate_image_file(file)
        
        if not is_valid:
            return jsonify({
                'success': False,
                'error': 'Validation Error',
                'message': error_msg
            }), 400
        
        # Get optional parameters
        process_immediately = request.form.get('process_immediately', 'true').lower() == 'true'
        camera_id = request.form.get('camera_id')
        gate_location = request.form.get('gate_location', 'unknown')
        
        # Save uploaded file
        image_service = ImageService()
        filename = get_secure_filename(file.filename)
        image_path = image_service.save_uploaded_image(file, filename)
        
        current_app.logger.info(f"Image uploaded: {image_path}")
        
        response_data = {
            'success': True,
            'message': 'Image uploaded successfully',
            'image_path': image_path,
            'process_immediately': process_immediately
        }
        
        # Process immediately if requested
        if process_immediately:
            anpr_service = AnprService()
            recognition_result = anpr_service.process_image(
                image_path=image_path,
                camera_id=camera_id,
                gate_location=gate_location
            )
            response_data['processing_result'] = recognition_result
        
        return jsonify(response_data), 200
    
    except Exception as e:
        current_app.logger.error(f"Error uploading image: {str(e)}", exc_info=True)
        return jsonify({
            'success': False,
            'error': 'Upload Error',
            'message': 'An error occurred while uploading the image'
        }), 500
