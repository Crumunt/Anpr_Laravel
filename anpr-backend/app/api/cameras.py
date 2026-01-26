"""
Camera Management API Endpoints

Handles camera enumeration, status checks, and camera stream management.
"""

from flask import Blueprint, jsonify, current_app, request

from app.services.camera_service import CameraService

# Create Blueprint
cameras_bp = Blueprint('cameras', __name__)


@cameras_bp.route('', methods=['GET'])
def list_cameras():
    """
    List all available cameras.
    
    Returns information about configured and detected cameras,
    including their status and capabilities.
    
    Query Parameters:
        - include_unavailable: Boolean, include unavailable cameras (default: false)
        
    Returns:
        JSON response with list of cameras
        
    Example Response:
        {
            "success": true,
            "cameras": [
                {
                    "id": "camera-0",
                    "name": "Main Entrance Camera",
                    "device_index": 0,
                    "status": "available",
                    "location": "Main Gate",
                    "resolution": {
                        "width": 1280,
                        "height": 720
                    },
                    "fps": 30
                }
            ],
            "total": 1
        }
    """
    try:
        include_unavailable = request.args.get('include_unavailable', 'false').lower() == 'true'
        
        camera_service = CameraService()
        cameras = camera_service.list_cameras(include_unavailable=include_unavailable)
        
        return jsonify({
            'success': True,
            'cameras': cameras,
            'total': len(cameras)
        }), 200
    
    except Exception as e:
        current_app.logger.error(f"Error listing cameras: {str(e)}", exc_info=True)
        return jsonify({
            'success': False,
            'error': 'Camera Service Error',
            'message': 'Unable to retrieve camera list'
        }), 500


@cameras_bp.route('/<camera_id>/status', methods=['GET'])
def get_camera_status(camera_id):
    """
    Get status of a specific camera.
    
    Args:
        camera_id: Camera identifier or device index
        
    Returns:
        JSON response with camera status and details
        
    Example Response:
        {
            "success": true,
            "camera": {
                "id": "camera-0",
                "status": "available",
                "is_streaming": false,
                "last_frame_time": "2025-01-15T10:30:00Z"
            }
        }
    """
    try:
        camera_service = CameraService()
        status = camera_service.get_camera_status(camera_id)
        
        if not status:
            return jsonify({
                'success': False,
                'error': 'Not Found',
                'message': f'Camera {camera_id} not found'
            }), 404
        
        return jsonify({
            'success': True,
            'camera': status
        }), 200
    
    except Exception as e:
        current_app.logger.error(f"Error getting camera status: {str(e)}", exc_info=True)
        return jsonify({
            'success': False,
            'error': 'Camera Service Error',
            'message': 'Unable to retrieve camera status'
        }), 500
