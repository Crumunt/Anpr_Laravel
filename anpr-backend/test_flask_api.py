"""
Simple test script for Flask ANPR API endpoints.

Run this script to test the Flask backend endpoints.
Make sure Flask is running first: python run.py
"""

import requests
import json
import os
from pathlib import Path

# Flask API base URL
FLASK_BASE_URL = "http://localhost:5000"
API_PREFIX = "/api/anpr"

# Test image path (create a simple test image or use an existing one)
TEST_IMAGE_PATH = None  # Set this to a path to an image file


def print_response(response, title="Response"):
    """Pretty print API response."""
    print(f"\n{'='*60}")
    print(f"{title}")
    print(f"{'='*60}")
    print(f"Status Code: {response.status_code}")
    try:
        print(f"Response JSON:")
        print(json.dumps(response.json(), indent=2))
    except:
        print(f"Response Text: {response.text}")
    print(f"{'='*60}\n")


def test_status():
    """Test the status/health check endpoint."""
    print("Testing: GET /api/anpr/status")
    try:
        response = requests.get(f"{FLASK_BASE_URL}{API_PREFIX}/status", timeout=5)
        print_response(response, "Status Endpoint")
        return response.status_code == 200
    except requests.exceptions.ConnectionError:
        print("ERROR: Cannot connect to Flask. Make sure Flask is running!")
        print("Run: python run.py")
        return False
    except Exception as e:
        print(f"ERROR: {str(e)}")
        return False


def test_cameras():
    """Test the cameras list endpoint."""
    print("Testing: GET /api/anpr/cameras")
    try:
        response = requests.get(f"{FLASK_BASE_URL}{API_PREFIX}/cameras", timeout=5)
        print_response(response, "Cameras Endpoint")
        return response.status_code == 200
    except Exception as e:
        print(f"ERROR: {str(e)}")
        return False


def test_upload(image_path):
    """Test the image upload endpoint."""
    if not image_path or not os.path.exists(image_path):
        print("SKIPPING: No test image provided or image not found")
        print("Set TEST_IMAGE_PATH in the script or pass image path as argument")
        return False
    
    print(f"Testing: POST /api/anpr/upload (with image: {image_path})")
    try:
        with open(image_path, 'rb') as f:
            files = {'file': (os.path.basename(image_path), f, 'image/jpeg')}
            data = {
                'process_immediately': 'true',
                'camera_id': 'camera-0',
                'gate_location': 'main-gate'
            }
            response = requests.post(
                f"{FLASK_BASE_URL}{API_PREFIX}/upload",
                files=files,
                data=data,
                timeout=30
            )
        print_response(response, "Upload Endpoint")
        return response.status_code == 200
    except Exception as e:
        print(f"ERROR: {str(e)}")
        return False


def test_process(image_path):
    """Test the image processing endpoint."""
    if not image_path or not os.path.exists(image_path):
        print("SKIPPING: No test image provided or image not found")
        return False
    
    print(f"Testing: POST /api/anpr/process (with image: {image_path})")
    try:
        with open(image_path, 'rb') as f:
            files = {'file': (os.path.basename(image_path), f, 'image/jpeg')}
            data = {
                'camera_id': 'camera-0',
                'gate_location': 'main-gate',
                'direction': 'entry'
            }
            response = requests.post(
                f"{FLASK_BASE_URL}{API_PREFIX}/process",
                files=files,
                data=data,
                timeout=30
            )
        print_response(response, "Process Endpoint")
        return response.status_code == 200
    except Exception as e:
        print(f"ERROR: {str(e)}")
        return False


def main():
    """Run all tests."""
    print("\n" + "="*60)
    print("Flask ANPR API Test Suite")
    print("="*60)
    print(f"Testing Flask at: {FLASK_BASE_URL}")
    print("="*60 + "\n")
    
    results = []
    
    # Test 1: Status check
    results.append(("Status Check", test_status()))
    
    # Test 2: Cameras list
    results.append(("Cameras List", test_cameras()))
    
    # Test 3: Upload (if image provided)
    image_path = TEST_IMAGE_PATH
    if image_path and os.path.exists(image_path):
        results.append(("Image Upload", test_upload(image_path)))
        results.append(("Image Process", test_process(image_path)))
    
    # Summary
    print("\n" + "="*60)
    print("Test Summary")
    print("="*60)
    for test_name, passed in results:
        status = "✓ PASSED" if passed else "✗ FAILED"
        print(f"{test_name}: {status}")
    print("="*60 + "\n")


if __name__ == "__main__":
    import sys
    
    # Allow image path as command line argument
    if len(sys.argv) > 1:
        TEST_IMAGE_PATH = sys.argv[1]
    
    main()
